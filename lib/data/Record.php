<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 24/02/16
 * Time: 5:39 PM
 */
class Record
{
    public $IP;
    public $BLOCKED = false;
    public $ATTEMPTS = 1;
    public $SERVICE;

    /** @var  LASTOFFENCETIME is the date stamp of the last time this record was needed to be accessed. Determines
     * whether record should be incremented or deleted */
    public $LASTOFFENCETIMES = Array();
    /** @var  BLOCKTIME is the date stamp of when this record/person was blocked. Used to determine when to unblock */
    public $BLOCKTIME = null;

    protected function createDateFromEntry($entry){
        $words = explode(" ", $entry);
        $pulledDate = "$words[0] $words[1] $words[2]";
        return date_create_from_format('M d G:i:s', $pulledDate);
    }
}