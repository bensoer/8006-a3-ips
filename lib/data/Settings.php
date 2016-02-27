<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 25/02/16
 * Time: 5:36 PM
 */

/**
 * Class Settings is a data class representing user settings for the ips system. These settings are created during
 * first run of the ips program, whether that be in settings mode or check mode.
 */
class Settings
{

    /** @var int timeLimit - how long the IP will be blocked in minutes before being unblocked */
    public $timeLimit = -1;
    /** @var int attemptLimit -  how many attempts can occur before it is concidered a potential threat */
    public $attemptLimit = 3;
    /** @var string - the directory of the log file to be checking for offences */
    public $logDir = "/var/log/secure";
    /** @var (null|DateTime) - The datetime of the last entry that was viewed in the log. This is used in later
     * startups of the ips so that it will only check newer logs since its last run */
    public $lastLogTime = null;
}