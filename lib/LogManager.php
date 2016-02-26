<?php

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

    public function __construct($dirToLogFile){

        if(!file_exists($dirToLogFile)){
            throw new InvalidArgumentException("Directory Passed Does Not Go To A File");
        }else{
            $this->logFileDir = $dirToLogFile;

            //TODO: password needs to be passed in as a parameter for this call to work properly
            exec("echo 'password' | sudo -S cat $dirToLogFile", $this->logFileContents);
            //$this->logFileContents = file($dirToLogFile);
        }
    }


    public function findNewLoginAttempts($lastSearchTimeStamp = null){
        //if null we start from the beginning
        if($lastSearchTimeStamp == null){

            var_dump($this->logFileContents);



        //else we start from after the timestamp in the log
        }else{




        }
    }


}