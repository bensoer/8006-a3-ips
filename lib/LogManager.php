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
class LogManager
{
    private $logFileDir = null;
    private $logFileContents = Array();

    public function __construct($dirToLogFile, $sudoPassword){

        if(!file_exists($dirToLogFile)){
            throw new InvalidArgumentException("Directory Passed Does Not Go To A File");
        }else{
            $this->logFileDir = $dirToLogFile;

            exec("echo '$sudoPassword' | sudo -S cat $dirToLogFile", $this->logFileContents);
            //$this->logFileContents = file($dirToLogFile);
        }
    }

    private function isGreaterThenDate($thresholdDate, $dateInQuestion){
        $formattedDateInQuestion = date_create_from_format('M d G:i:s', $dateInQuestion);

        /*echo "DATE IN QUESTION";
        var_dump($formattedDateInQuestion);
        echo "THRESHOLD DATE";
        var_dump($thresholdDate);

        if($dateInQuestion > $thresholdDate){
            echo "DATE IN QUESTION IS GREATER / NEWER";
        }elsE{
            echo "THRESHOLD DATE IS GREATER / NEWER";
        }*/

        return ($dateInQuestion > $thresholdDate);
    }


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

    public function getTimeStampOfLastEntry(){
        $lastEntry = $this->logFileContents[count($this->logFileContents)-1];

        $words = explode(" ", $lastEntry);
        $pulledDate = "$words[0] $words[1] $words[2]";

        //echo $lastEntry . "\n";
        //echo $pulledDate . "\n";

        return date_create_from_format('M d G:i:s', $pulledDate);

    }



    public function getAllEntries(){
        return $this->logFileContents;
    }


    public function createRecordOfEntry($entry){

        if(ServiceChecker::sshd($entry)){
            return new SSHRecord($entry);
        }

        if(ServiceChecker::telnet($entry)){
            return new TelnetRecord($entry);
        }



    }
}