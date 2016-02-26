<?php
require_once('./lib/tools/ArgParcer.php');
require_once('./lib/data/Settings.php');
require_once('./lib/LogManager.php');
/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 24/02/16
 * Time: 5:33 PM
 */

/**
 * PARAMETERS:
 * -m <check|create|read|update|delete|settings> - Specify mode/activity to run.
 * -i <ip> - The IP of the accessor in question
 * -s <block|unblock> - The state the record should be
 * -p <protocol> - Name of the protocol to be looking for counts of
 *
 * SETTING MODE PARAMETERS:
 * -tl <timelimit> - The time limit an IP stays blocked. Value of -1 means no limit
 * -al <attemptlimit> - The number of times an attempt can be made before it is blocked
 * -ld <logdir> - The directory of the log file being monitored
 */


define("SETTINGDIR","./settings.ipsconf");
define("RECORDDIR","./records.ipsconf");

/**
 * @param $argc
 * @param $argv
 * @return int
 */
function main($argc, $argv){


    //get arguments
    $formattedArguments = ArgParcer::formatArguments($argv);
    $apInstance = ArgParcer::getInstance($formattedArguments);

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
    }

    //based on mode decide what needs to be done next
    $mode = $apInstance->getValue("-m");
        //if to check
        if(strcmp($mode,"check")==0){
            //get login attempts list from log file - NOTE must concider date range so that you don't overlap
            $logManager = new LogManager($settings->logDir);
            $logManager->findNewLoginAttempts();

            //check for matching known records
            //if new record add the record

            //if known increment its count

            //check for records which have made too many offences in given time span
            //foreach one, block them

            //check for records that have been blocked long enough
            //foreach one, unblock them
        }




    //serialize record manager

    //serialize settings parameters

    return 0;
}
exit(main($argc,$argv));