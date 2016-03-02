<?php
require_once('./lib/tools/ServiceChecker.php');
require_once('./lib/data/Record.php');
require_once('./lib/data/SSHRecord.php');
require_once('./lib/data/TelnetRecord.php');


/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 24/02/16
 * Time: 6:14 PM
 */

/**
 * Class LogManager handles reading and parcing out content from the passed in log file given at initialization. It also
 * generates Record objects from log entries
 */
class LogManager
{
    private $logFileDir = null;
    private $logFileContents = Array();

    /**
     * LogManager constructor. Initializes the object, checks the validity of the passed in log file. If the log file
     * does not exist or is invalid, an InvalidArgumentException will be thrown
     * @param $dirToLogFile String - the directory file path to the log file being read in by the LogManager
     * @param $sudoPassword String - the password as reading in these files requires sudo user permissions
     */
    public function __construct($dirToLogFile, $sudoPassword){

        if(!file_exists($dirToLogFile)){
            throw new InvalidArgumentException("Directory Passed Does Not Go To A File");
        }else{
            $this->logFileDir = $dirToLogFile;

            exec("echo '$sudoPassword' | sudo -S cat $dirToLogFile", $this->logFileContents);
            //$this->logFileContents = file($dirToLogFile);
        }
    }

    /**
     * isGreaterThenDate is a helper method that determines if the passed in dateInQuestion String is greater then the
     * thresholdDate object
     * @param $thresholdDate DateTime - the DateTime object being compared against
     * @param $dateInQuestion String - the date string that needs to be parced out into a DateTime object to comapre
     * whether it is greater then the thresholdDate DateTime object
     * @return bool - Status of whether the dateInQuestion String date is greater then the thresholdDate DateTime object
     */
    private function isGreaterThenDate($thresholdDate, $dateInQuestion){
        $formattedDateInQuestion = date_create_from_format('M d G:i:s', $dateInQuestion);

        //var_dump($formattedDateInQuestion);

        /*echo "DATE IN QUESTION";
        var_dump($formattedDateInQuestion);
        echo "THRESHOLD DATE";
        var_dump($thresholdDate);

        if($dateInQuestion > $thresholdDate){
            echo "DATE IN QUESTION IS GREATER / NEWER";
        }elsE{
            echo "THRESHOLD DATE IS GREATER / NEWER";
        }*/

        //$d1->format('U') < $d2->format('U')
        //print("Formatted Date: " . $formattedDateInQuestion->format('u'));
        //print("threshold Date: " . $thresholdDate->format('u'));
        //var_dump($thresholdDate);
        //var_dump($formattedDateInQuestion);

        return ($formattedDateInQuestion > $thresholdDate);
    }


    /**
     * findNewLoginAttempts searches through the log file for new offences. If the lastSearchTimeStamp is supplied,
     * findNewLoginAttempts will then filter the log file to make sure it does not read any logs until after that passed
     * in date
     * @param null $lastSearchTimeStamp - A DateTime object of the datestamp to start searching after in the logs for offences
     * @return array - a filtered list of log String entries that are offences
     */
    public function findNewLoginAttempts($lastSearchTimeStamp = null){
        $filterList = Array();
        //if null we start from the beginning
        if($lastSearchTimeStamp == null){

            //var_dump($this->logFileContents);

            foreach($this->logFileContents as $logEntry){
                if(ServiceChecker::isAnOffenceToAService($logEntry)){
                    $filterList[] = $logEntry;
                }
            }



        //else we start from after the timestamp in the log
        }else{

            //print("There is a threshold \n");

            foreach($this->logFileContents as $logEntry){
                $words = explode(" ", $logEntry);
                $pulledDate = "$words[0] $words[1] $words[2]";


                if($this->isGreaterThenDate($lastSearchTimeStamp, $pulledDate)){

                    if(ServiceChecker::isAnOffenceToAService($logEntry)){
                        $filterList[] = $logEntry;
                    }
                }
            }
        }

        return $filterList;
    }

    /**
     * getTimeStampOfLastEntry is a client helper method to get the timestamp of the last log entry. It is parsed out and
     * returned as a DAteTime object
     * @return DateTime - the parsed out date from the logs converted to a DateTime object
     */
    public function getTimeStampOfLastEntry(){
        $lastEntry = $this->logFileContents[count($this->logFileContents)-1];

        $words = explode(" ", $lastEntry);

        $pulledDate = "";
        if(empty($words[1])){
            $pulledDate = "$words[0] $words[1] $words[2] $words[3]";
        }else{
            $pulledDate = "$words[0] $words[1] $words[2]";
        }


        $dateTime = date_create_from_format('M d G:i:s', $pulledDate);
        if($dateTime === false){
            $dateTime = date_create_from_format('M  d G:i:s', $pulledDate);
        }
        if($dateTime === false){
            throw new ErrorException("Failed To Create Date From TimeStamp");
        }

        return $dateTime;

    }


    /**
     * a client helper object that returns all log entries read in from the log file
     * @return array - An array of each entry in the log file
     */
    public function getAllEntries(){
        return $this->logFileContents;
    }


    /**
     * createRecordOfEntry takes the passed in log entry and checks what service is suitable as an offence for it. It
     * then generates a record object for it
     * @param $entry
     * @return SSHRecord|TelnetRecord - The Record best suited for the given entry.
     */
    public function createRecordOfEntry($entry){

        if(ServiceChecker::sshd($entry)){
            return new SSHRecord($entry);
        }

        if(ServiceChecker::telnet($entry)){
            return new TelnetRecord($entry);
        }



    }
}
