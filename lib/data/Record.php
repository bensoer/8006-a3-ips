<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 24/02/16
 * Time: 5:39 PM
 */

/**
 * Class Record is a data object representing a record of a user with the same IP over the same service who has attempted
 * to login to the system. Within the record stores their number of attempts which is used to determine whether the user
 * should be blocked or not
 */
class Record
{
    /** @var  IP String - the IP of the offender */
    public $IP;
    /** @var bool - state of whether this record is currently being blocked or not */
    public $BLOCKED = false;
    /** @var int - the number of attempts this IP and service have tried to make logging into the system */
    public $ATTEMPTS = 1;
    /** @var  String - the service the offender is using for this record */
    public $SERVICE;

    /** @var  LASTOFFENCETIME is the date stamp of the last time this record was needed to be accessed. Determines
     * whether record should be incremented or deleted */
    public $LASTOFFENCETIMES = Array();
    /** @var  BLOCKTIME is the date stamp of when this record/person was blocked. Used to determine when to unblock */
    public $BLOCKTIME = null;

    /**
     * createDateFromEntry is a helper method used by implementing classes to generate a date object from the record
     * entry when trying to parse out a record object from the log entry
     * @param $entry String - the log entry containing date information
     * @return DateTime - the date object generated from date information parsed out of the log entry
     */
    protected function createDateFromEntry($entry){
        $words = explode(" ", $entry);
        $pulledDate = "$words[0] $words[1] $words[2]";
        return date_create_from_format('M d G:i:s', $pulledDate);
    }
}