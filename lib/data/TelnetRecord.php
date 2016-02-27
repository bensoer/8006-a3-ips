<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 27/02/16
 * Time: 11:39 AM
 */
class TelnetRecord extends Record
{

    public function __construct($logEntry){

        if(strpos($logEntry, "audit")){
            $this->createFromMessagesLog($logEntry);
        }else{
            $this->createFromSecureLog($logEntry);
        }


    }

    private final function createFromMessagesLog($logEntry){
        //Feb 27 11:43:21 ironhide audit: <audit-1112> pid=13145 uid=0 auid=4294967295 ses=4294967295 subj=system_u:system_r:remote_login_t:s0 msg='op=login id=1000 exe="/usr/bin/login" hostname=localhost.localdomain addr=127.0.0.1 terminal=pts/4 res=failed'

        $words = explode(" ", $logEntry);


        $ipseg = $words[15];
        $ip = substr(strpos($ipseg,"=")+1, strlen($ipseg));

        $this->IP = $ip;
        $this->SERVICE = "telnet";

        $this->ATTEMPTS = 1;
        $this->LASTOFFENCETIMES[] = $this->createDateFromEntry($logEntry);


    }

    private final function createFromSecureLog($logEntry){
        //Feb 27 11:43:21 ironhide login: FAILED LOGIN 1 FROM localhost.localdomain FOR bensoer, Authentication failure

        throw new Exception("TelnetRecord:createFromSecureLog - Not Implemented");
    }
}