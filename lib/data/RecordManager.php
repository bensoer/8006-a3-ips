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

    public function getRecordIfExists($ip, $service){

        foreach($this->records as $record){
            if(strcmp($record->IP, $ip)==0 && strcmp($record->SERVICE, $service)==0){
                return $record;
            }
        }

        return null;
    }

    public function updateRecord($ip, $service, Record $record){

        for($i = 0; $i < count($this->records); $i++){
            if(strcmp($this->records[$i]->IP, $ip)==0 && strcmp($this->records[$i]->SERVICE, $service)==0){

                $this->records[$i] = $record;
                break;
            }
        }
    }

    public function deleteRecord(Record $toBeDeletedRecord){
        foreach($this->records as $record){
            if(strcmp($record->IP, $toBeDeletedRecord->IP)==0 && strcmp($record->SERVICE, $toBeDeletedRecord->SERVICE)==0){
                return $record;
            }
        }
    }

    public function getAllRecords(){
        return $this->records;
    }

}