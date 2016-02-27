<?php
require_once("./lib/data/SSHRecord.php");
/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 27/02/16
 * Time: 10:35 AM
 */
class SSHRecord extends Record
{


    public function __construct($logEntry){

        if(strpos($logEntry, "audit")){
            $this->createFromMessagesLog($logEntry);
        }else{
            $this->createFromSecureLog($logEntry);
        }


    }

    private final function createFromMessagesLog($logEntry){
        //Feb 27 10:30:04 ironhide audit: <audit-1100> pid=7368 uid=0 auid=4294967295 ses=4294967295 subj=system_u:system_r:sshd_t:s0-s0:c0.c1023 msg='op=password acct="bensoer" exe="/usr/sbin/sshd" hostname=? addr=127.0.0.1 terminal=ssh res=failed'

        $words = explode(" ", $logEntry);

        $serviceseg = $words[13];
        $service = substr(strrpos($serviceseg,"/")+1, strlen($serviceseg));

        $ipseg = $words[15];
        $ip = substr(strpos($ipseg,"=")+1, strlen($ipseg));

        $this->IP = $ip;
        $this->SERVICE = $service;

        $this->ATTEMPTS = 1;
        $this->LASTOFFENCETIMES[] = $this->createDateFromEntry($logEntry);


    }

    private final function createFromSecureLog($logEntry){
        $words = explode(" ", $logEntry);

        if(strcmp($words[10],"logname=")==0 && strcmp($words[13],"tty=ssh")==0){
            //print("Found this record");
            $ipseg = $words[15];
            $this->IP = substr($ipseg,6,strlen($ipseg));

        }else{
            $this->IP = $words[10];
        }

        $servseg = $words[4];
        $service = substr($servseg, 0, strpos($servseg, "["));
        $this->SERVICE = $service;

        $this->ATTEMPTS = 1;
        $this->LASTOFFENCETIMES[] = $this->createDateFromEntry($logEntry);

    }
}