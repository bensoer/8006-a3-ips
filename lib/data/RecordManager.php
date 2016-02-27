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
        for($i=0; $i < $this->records; $i++){
            if(strcmp($this->records[$i]->IP, $toBeDeletedRecord->IP)==0 && strcmp($this->records[$i]->SERVICE, $toBeDeletedRecord->SERVICE)==0){
                unset($this->records[$i]);
                break;
            }
        }
    }

    public function getAllRecords(){
        return $this->records;
    }

    private function getTotalMinutesFromDif(DateInterval $diff){
        $totalMinutes = 0;

        $totalMinutes += ($diff->y * 365 * 24* 60);
        $totalMinutes += ($diff->m * 30 * 24 * 60);
        $totalMinutes += ($diff->d * 24 * 60);
        $totalMinutes += ($diff->h * 60);
        $totalMinutes += $diff->i;

        return $totalMinutes;
    }

    public function isOffendingFrequently(Record $record, $offenceThreshold = 3){

        $intervals = Array();

        for($i = 0; $i < count($record->LASTOFFENCETIMES) - 1 ; $i++){
            $intervals[] = date_diff($record->LASTOFFENCETIMES[$i], $record->LASTOFFENCETIMES[$i+1]);
        }

        $isNotOffendingFrequently = false;

        //check for breaks, if the attempts are greater then 3 min apart, they may not be offences. Attacks will
        //likely be within short bursts, attempts of eachother. Otherwise this could be someone trying to get in at different
        //session times
        foreach($intervals as $interval){
            $totalMinutes = $this->getTotalMinutesFromDif($interval);
            print($totalMinutes);
            if($totalMinutes > $offenceThreshold){
                //print("found out of bounds threshold \n");
                $isNotOffendingFrequently = true;
            }
        }


        //now check if the time differences though are the exact same, this could infer a bot is trying to enter. And
        //could just be entering very slowly
        $isCommon = true;
        for($i = 0; $i < count($intervals) - 1 ; $i++){
            $commonSet = $intervals[$i + 1];
            if($intervals[$i]->y != $commonSet->y){
                $isCommon = false;
                break;
            }
            if($intervals[$i]->i != $commonSet->i){
                $isCommon = false;
                break;
            }
            if($intervals[$i]->d != $commonSet->d){
                $isCommon = false;
                break;
            }
            if($intervals[$i]->h != $commonSet->h){
                $isCommon = false;
                break;
            }
            if($intervals[$i]->m != $commonSet->m){
                $isCommon = false;
                break;
            }
            if($intervals[$i]->s != $commonSet->s){
                $isCommon = false;
                break;
            }
        }

        return ($isCommon || !$isNotOffendingFrequently);

    }

}