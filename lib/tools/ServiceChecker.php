<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 25/02/16
 * Time: 7:41 PM
 */

/**
 * Class ServiceChecker checks if a log entry is an offence to a given service
 */
class ServiceChecker
{

    /**
     * isAnOffenceToAService is a generic method that checks against all possible service checks implemented by
     * the ServiceChecker to determine if any one of them is valid
     * @param $logEntry - The logEntry being checked against
     * @return bool - Status of whether the passed in log is an offence on any of the services
     */
    public static function isAnOffenceToAService($logEntry){
        return (self::sshd($logEntry) || self::telnet($logEntry));
    }

    /**
     * sshd determines if the passed in log entry is an offence to sshd login attempts
     * @param $logEntry String - the log entry being checked if it contains content that would match a log entry attempt
     * @return bool - Status of whether the log entry DOES contain content that would match a log entry attempt
     */
    public static function sshd($logEntry){
        //if using /log/var/secure
        if(strpos($logEntry, "sshd") && ( strpos($logEntry, "Failed password") || strpos($logEntry, "authentication failures") )){
            return true;
        //if using /log/var/messages
        }else if(strpos($logEntry, "sshd") && strpos($logEntry, "msg='op=password") && strpos($logEntry, "res=failed")){
            return true;
        }

        return false;
    }

    /**
     * telnet determines if the passed in log entry is an offence to telnet login attempts
     * @param $logEntry String - the log entry being checked if it contains content that would match a log entry attempt
     * @return bool - Status of whether the log entry DOES contain content that would match a log entry attempt
     */
    public static function telnet($logEntry){
        //if using /log/var/secure

        //good luck figuring that out champ


        //Feb 29 15:35:11 datacomm audit: <audit-1112> pid=7735 uid=0 auid=4294967295 ses=4294967295 msg='op=login id=0 exe="/usr/bin/login" hostname=::ffff:192.168.0.10 addr=::ffff:192.168.0.10 terminal=pts/7 res=failed'

        //if using /log/var/messages
        if(strpos($logEntry, "msg='op=login") && strpos($logEntry, "res=failed")){
            return true;
        }

        return false;
    }

}
