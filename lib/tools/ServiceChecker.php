<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 25/02/16
 * Time: 7:41 PM
 */
class ServiceChecker
{

    public static function sshd($logEntry){
        if(strpos($logEntry, "sshd") && ( strpos($logEntry, "Failed password") || strpos($logEntry, "authentication failures") )){
            return true;
        }else{
            return false;
        }
    }

}