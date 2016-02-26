<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 25/02/16
 * Time: 5:36 PM
 */
class Settings
{

    /** @var int timeLimit - how long the IP will be blocked in minutes before being unblocked */
    public $timeLimit = -1;
    /** @var int attemptLimit -  how many attempts can occur before it is concidered a potential threat */
    public $attemptLimit = 3;
    public $logDir = "/var/log/secure";
    public $lastLogTime = null;
}