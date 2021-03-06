<?php
require_once('./lib/tools/ArgParcer.php');
require_once('./lib/data/Settings.php');
require_once('./lib/LogManager.php');
require_once('./lib/data/RecordManager.php');
require_once('./lib/NetFilterManager.php');
/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 24/02/16
 * Time: 5:33 PM
 */

/**
 * PARAMETERS:
 * -m <check|settings> - Specify mode/activity to run.
 * -p <password> - The password needed to get sudo access
 *
 * SETTING MODE PARAMETERS:
 * -tl <timelimit> - The time limit an IP stays blocked. Value of -1 means no limit
 * -al <attemptlimit> - The number of times an attempt can be made before it is blocked
 * -ld <logdir> - The directory of the log file being monitored
 */


define("SETTINGDIR","./settings.ipsconf");
define("RECORDDIR","./records.ipsconf");

date_default_timezone_set('America/Los_Angeles');



/**
 * main is the main entry point of the ips program
 * @param $argc Integer - the number of arguments in the arv array
 * @param $argv Array - the arguments passed as initializer variables
 * @return int - Status of whether the program ran successfuly or failed. 0 means success, 1 means failure
 */
function main($argc, $argv){


    //get arguments
    $formattedArguments = ArgParcer::formatArguments($argv);
    $apInstance = ArgParcer::getInstance($formattedArguments);


    $sudoPassword = $apInstance->getValue("-p");
    if($sudoPassword == null || $apInstance->getValue("-m") == null){
        print("Invalid Parameters. Required Parameters Were Not Passed. Expected Use:\n");
        print("ips.php -m <mode> -p <sudopassword> \n");
        return 1;
    }

    $settings = null;
    $newSettingsCreated = false;
    $newRecordManagerCreated = false;

    //deserialize settings parameters
    if(file_exists(SETTINGDIR)){
        $fileContents = file_get_contents(SETTINGDIR);
        $settings = unserialize($fileContents);
    }else{
        print("No Settings Config Could Be Found. Creating A New One\n");
        $settings = new Settings();
        $newSettingsCreated = true;
    }

    $recordManager = null;
    //deserialize record manager
    if(file_exists(RECORDDIR)){
        $recordFileContents = file_get_contents(RECORDDIR);
        $recordManager = unserialize($recordFileContents);
    }else{
        print("No Record Manager Found. Creating A New One\n");
        $newRecordManagerCreated = true;
        $recordManager = new RecordManager();
    }

    //based on mode decide what needs to be done next
    $mode = $apInstance->getValue("-m");
        //if to check
        if(strcmp($mode,"check")==0){
            print("Check Mode Activated. Checking For New Threats \n");
            //get login attempts list from log file - NOTE must concider date range so that you don't overlap
            $logManager = new LogManager($settings->logDir, $sudoPassword);
            $loginAttempts = $logManager->findNewLoginAttempts($settings->lastLogTime);
            $settings->lastLogTime = $logManager->getTimeStampOfLastEntry();

            //var_dump($logManager->getAllEntries());
            var_dump($loginAttempts);
            //var_dump($logManager->getTimeStampOfLastEntry());

            //check for matching known records
            foreach($loginAttempts as $attemptString){
                $record = $logManager->createRecordOfEntry($attemptString);

                $result = $recordManager->getRecordIfExists($record->IP, $record->SERVICE);
                //if new record add the record
                if($result == null){

                    //record is new
                    $recordManager->addRecord($record);

                //if known increment its count
                }else{

                    //if it is blocking already leave it alone
                    if($result->BLOCKTIME != null){

                        continue;


                    }else{

                        $result->ATTEMPTS += 1;
                        $result->LASTOFFENCETIMES[] = $record->LASTOFFENCETIMES[0];

                        //check if this record has made too many offences. If so block them
                        if($result->ATTEMPTS >= $settings->attemptLimit){

                            print("Too Many Offences. Checking Threat Of Offences \n");

                            if($recordManager->isOffendingFrequently($result)){
                                print("Threat IS Offending Frequently. Blocking \n");
                                $netFilterManager = new NetFilterManager($sudoPassword);
                                $netFilterManager->block('tcp', $result->IP);
                                $result->BLOCKTIME = date_create();
                            }else{

                                $result->ATTEMPTS = 1;
                            }
                        }

                        $recordManager->updateRecord($result->IP, $result->SERVICE, $result);
                    }
                }
            }
            //var_dump($recordManager->getAllRecords());

            print("Now Checking Records For Offences That Can Be Unblocked \n");
            if($settings->timeLimit == -1){
                print("Can't Do Any Unblocking. Timeout Limit Is Set To Inifinite \n");

            }else{
                //check if anything can be unblocked
                $allRecords = $recordManager->getAllRecords();
                foreach($allRecords as $record){

                    if($record->BLOCKTIME != null){
                        //check if record has been blocked long enough

                        $datetime = date_create();
                        $difference = date_diff($record->BLOCKTIME, $datetime);

                        $days = $difference->d;
                        $hours = $difference->h;
                        $minutes = $difference->i;

                        $totalMinutes = ($days * 24 * 60) + ($hours * 60) + ($minutes);

                        if($totalMinutes > $settings->timeLimit){

                            print("Found A Record Whose Block Time Has Exceeded The Time Limit. Unblocking \n");
                            $netFilterManager = new NetFilterManager($sudoPassword);
                            $netFilterManager->unblock('tcp', $record->IP);
                            $recordManager->deleteRecord($record);

                        }
                    }
                }
            }
        }

        //if the mode is to update / set settings
        if(strcmp($mode,"settings")==0){
            print("Settings Mode Detected. Altering Settings To Parameters \n");
            //tl al ld
            $timeLimit = $apInstance->getValue("-tl");
            $attemptLimit = $apInstance->getValue("-al");
            $logDir = $apInstance->getValue("-ld");

            if($timeLimit != null){
                print("Settings - New Time Limit Supplied. Adjusting \n");
                $settings->timeLimit = $timeLimit;
            }

            if($attemptLimit != null){
                print("Settings - New Attempt Limit Supplied. Adjusting \n");
                $settings->attemptLimit = $attemptLimit;
            }

            if($logDir != null){
                print("Settings - New Log Directory Supplied. Adjusting \n");
                $settings->logDir = $logDir;
            }
        }


    print("Now Serializing Content \n");
    //serialize record manager
    $recordManagerString = serialize($recordManager);
    file_put_contents(RECORDDIR, $recordManagerString);

    //serialize settings parameters
    $settingsString = serialize($settings);
    file_put_contents(SETTINGDIR, $settingsString);


    return 0;
}
exit(main($argc,$argv));
