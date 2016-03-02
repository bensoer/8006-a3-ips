<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 24/02/16
 * Time: 5:40 PM
 */

/**
 * Class RecordManager is in charge of the management of the main records array and allow CRUD actions to be taken
 * against it in a managed way.
 */
class RecordManager
{

    private $records = Array();

    /**
     * addRecord adds the passed in record to the end of the records array
     * @param Record $record - A record being added to the records array of the RecordManager
     */
    public function addRecord(Record $record){
        $this->records[] = $record;
    }

    /**
     * getRecordIfExists attempts to find a record matching the passed in ip and service parameters in the records
     * array. If it can not be found this method returns null
     * @param $ip String - the ip of the record we are trying to find
     * @param $service String - the service of the record we are trying to find
     * @return (null|Record) - the found record or null if it can not be found
     */
    public function getRecordIfExists($ip, $service){

        foreach($this->records as $record){
            if(strcmp($record->IP, $ip)==0 && strcmp($record->SERVICE, $service)==0){
                return $record;
            }
        }

        return null;
    }

    /**
     * updateRecord updates a record that matches the passed in ip and service parameters. Once a match is found, the
     * mathcing record is then completely replaced by the passed in record
     * @param $ip String - the ip of the record we are trying to find and update
     * @param $service String - the service of the record we are trying to find and update
     * @param Record $record - the record that will be replacing the record matching the passed in ip and service
     */
    public function updateRecord($ip, $service, Record $record){

        //var_dump($record);
        //var_dump($this->records);

        for($i = 0; $i < count($this->records); $i++){
            if(strcmp($this->records[$i]->IP, $ip)==0 && strcmp($this->records[$i]->SERVICE, $service)==0){

                $this->records[$i] = $record;
                break;
            }
        }
    }

    /**
     * deleteRecord deletes the first record it finds that matches the passed in record's IP and SERVICE values
     * @param Record $toBeDeletedRecord - the record that is going to be deleted from the RecordManager
     */
    public function deleteRecord(Record $toBeDeletedRecord){

        //var_dump($toBeDeletedRecord);
        //var_dump($this->records);

        for($i=0; $i < $this->records; $i++){
            if(strcmp($this->records[$i]->IP, $toBeDeletedRecord->IP)==0 && strcmp($this->records[$i]->SERVICE, $toBeDeletedRecord->SERVICE)==0){
                unset($this->records[$i]);
                break;
            }
        }
    }

    /**
     * getAllRecords returns the record array stored in the RecordManager
     * @return array - the records array managed by the RecordManager
     */
    public function getAllRecords(){
        return $this->records;
    }

    /**
     * getTotalMinutesFromDif is a helper method for calculating how many minutes are in a DateInterval. DateIntervals
     * seperate out how many months,days,hours, etc and do not count assumulatively, so this calculation brings them
     * all together
     * @param DateInterval $diff - the DateInterval object we are calculating the total minutes from by parsing out and
     * doing math to the year, month, day, hour and minutes attributes
     * @return int - the total number of minutes in the DateInterval
     */
    private function getTotalMinutesFromDif(DateInterval $diff){
        $totalMinutes = 0;

        $totalMinutes += ($diff->y * 365 * 24* 60);
        $totalMinutes += ($diff->m * 30 * 24 * 60);
        $totalMinutes += ($diff->d * 24 * 60);
        $totalMinutes += ($diff->h * 60);
        $totalMinutes += $diff->i;

        return $totalMinutes;
    }

    /**
     * isOffendingFrequently determines if a passed in record has been offending regularily enough to be on grounds to
     * being blocked. isOffendingFrequently first gets the interval times between each offence. It then checks if any of
     * them have occured at a greater time then the offence threshold. Being greater then the offenceThreshold assumes
     * then the offences may have been from seperate login attempts. Next it then checks all intervals if their values are
     * identical. This could mean that the attacker is a bot and is sending attempts at frequencies lower then the offenceThreshold
     * @param Record $record - the record we are determining if is offending
     * @param int $offenceThreshold - the number of minutes an interval has to be between to be concidered an offence
     * @return bool - whether or not the passed in record is offending. TRUE = they are offending and should be blocked
     * FALSE = they are not offending often enough and should not be blocked
     */
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
            //print($totalMinutes);
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
