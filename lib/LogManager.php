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
    private $logFileContents = null;

    public function __construct($dirToLogFile){

        if(!file_exists($dirToLogFile)){
            throw new InvalidArgumentException("Directory Passed Does Not Go To A File");
        }else{
            $this->logFileDir = $dirToLogFile;

            $this->logFileContents = file($dirToLogFile);
        }
    }

    public function findNewLoginAttempts($lastSearchTimeStamp = null){
        //if null we start from the beginning
        if($lastSearchTimeStamp == null){



        //else we start from after the timestamp in the log
        }else{




        }
    }


}