<?php
require_once("./lib/data/SSHRecord.php");
/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 27/02/16
 * Time: 10:35 AM
 */

/**
 * Class SSHRecord is an extension of Record with the added ability to specialize in creating records for the SSH service.
 * This allows unique implementations to be created specificaly for this service on various different logging files
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

    /**
     * createFromMessagesLog parses the logEntry based on the assumption that it is from the /var/log/messages file
     * @param $logEntry String - the log entry from the /var/log/messages file to be parsed into a Record object
     */
    private final function createFromMessagesLog($logEntry){
        //Feb 27 10:30:04 ironhide audit: <audit-1100> pid=7368 uid=0 auid=4294967295 ses=4294967295 subj=system_u:system_r:sshd_t:s0-s0:c0.c1023 msg='op=password acct="bensoer" exe="/usr/sbin/sshd" hostname=? addr=127.0.0.1 terminal=ssh res=failed'

        //print("creating sshd from message log \n");

        $words = explode(" ", $logEntry);

        if(strpos($words[13], "sshd")){
            $serviceseg = $words[13];
            $service = substr($serviceseg, strrpos($serviceseg,"/")+1, (strlen($serviceseg)-strrpos($serviceseg,"/")+1));
        }else if(strpos($words[14], "sshd")){
            $serviceseg = $words[14];
            $service = substr($serviceseg, strrpos($serviceseg,"/")+1, (strlen($serviceseg)-strrpos($serviceseg,"/")+1));
        }else{
            throw new ErrorException("Can't Find Service Name");
        }


        if(strpos($words[15],"addr") !== false){
            $ipseg = $words[15];
            $ip = substr($ipseg, strpos($ipseg,"=")+1, (strlen($ipseg)-strpos($ipseg,"=")+1));
        }else if(strpos($words[16], "addr") !== false){
            $ipseg = $words[16];
            $ip = substr($ipseg, strpos($ipseg,"=")+1, (strlen($ipseg)-strpos($ipseg,"=")+1));
        }else{
            var_dump($words[15]);
            var_dump($words[16]);
            print(strpos($words[16], "addr"));

            throw new ErrorException("Can't Find IP Address");
        }

        $this->IP = $ip;
        $this->SERVICE = $service;

        $this->ATTEMPTS = 1;
        $this->LASTOFFENCETIMES[] = $this->createDateFromEntry($logEntry);

    }

    /**
     * createFromSecureLog parses the logEntry based on teh assumption that it is from the /var/log/secure file
     * @param $logEntry String - the log entry from the /var/log/secure file to be parsed into a Record object
     */
    private final function createFromSecureLog($logEntry){
        $words = explode(" ", $logEntry);

        if(strcmp($words[10],"logname=")==0 && strcmp($words[13],"tty=ssh")==0){
            //print("Found this record");
            $ipseg = $words[15];
            $this->IP = substr($ipseg,6,strlen($ipseg)-6);

        }else{

            if(strcmp($words[10],"from")){
                $this->IP = $words[11];
            }else{
                $this->IP = $words[10];
            }

        }

        $servseg = $words[4];
        $service = substr($servseg, 0, strpos($servseg, "["));
        $this->SERVICE = $service;

        $this->ATTEMPTS = 1;
        $this->LASTOFFENCETIMES[] = $this->createDateFromEntry($logEntry);

    }
}
