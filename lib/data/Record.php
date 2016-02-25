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
    public $PROTOCOL;

    /** @var  LASTOFFENCETIME is the date stamp of the last time this record was needed to be accessed. Determines
     * whether record should be incremented or deleted */
    public $LASTOFFENCETIME;
    /** @var  BLOCKTIME is the date stamp of when this record/person was blocked. Used to determine when to unblock */
    public $BLOCKTIME;
}