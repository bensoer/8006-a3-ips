<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 25/02/16
 * Time: 7:41 PM
 */
class ServiceChecker
{

    public static function isAnOffenceToAService($logEntry){
        return (self::sshd($logEntry) || self::telnet($logEntry));
    }

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

    public static function telnet($logEntry){
        //if using /log/var/secure

        //good luck figuring that out champ

        //if using /log/var/messages
        if(strpos($logEntry, "subj=system_u:system_r:remote_login_t:s0 msg='op=login") && strpos($logEntry, "res=failed")){
            return true;
        }

        return false;
    }

}