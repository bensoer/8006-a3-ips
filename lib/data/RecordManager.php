<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 24/02/16
 * Time: 5:40 PM
 */
class RecordManager
{

    private $records = Array();

    public function __construct(){

    }

    public function addRecord(Record $record){
        $this->records[] = $record;
    }

    public function getRecord($ip, $protocol){

        foreach($this->records as $record){
            if(strcmp($record->IP, $ip)==0 && strcmp($record->PROTOCOL, $protocol)==0){
                return $record;
            }
        }
    }

    public function deleteRecord(Record $toBeDeletedRecord){
        foreach($this->records as $record){
            if(strcmp($record->IP, $toBeDeletedRecord->IP)==0 && strcmp($record->PROTOCOL, $toBeDeletedRecord->PROTOCOL)==0){
                return $record;
            }
        }
    }



}