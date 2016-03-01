<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 27/02/16
 * Time: 11:39 AM
 */

/**
 * Class TelnetRecord is an extension of Record with the added ability to specialize in creating records for the Telnet service.
 * This allows unique implementations to be created specificaly for this service on various different logging files
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

    /**
     * createFromMessagesLog parses the logEntry based on the assumption that it is from the /var/log/messages file
     * @param $logEntry String - the log entry from the /var/log/messages file to be parsed into a Record object
     */
    private final function createFromMessagesLog($logEntry){
        //Feb 27 11:43:21 ironhide audit: <audit-1112> pid=13145 uid=0 auid=4294967295 ses=4294967295 subj=system_u:system_r:remote_login_t:s0 msg='op=login id=1000 exe="/usr/bin/login" hostname=localhost.localdomain addr=127.0.0.1 terminal=pts/4 res=failed'

        //Feb 29 15:35:11 datacomm audit: <audit-1112> pid=7735 uid=0 auid=4294967295 ses=4294967295 msg='op=login id=0 exe="/usr/bin/login" hostname=::ffff:192.168.0.10 addr=::ffff:192.168.0.10 terminal=pts/7 res=failed'

        $words = explode(" ", $logEntry);

        $ipseg = null;
        $ip = null;
        if(strpos($words[15], "addr")){
          $ipseg = $words[15];
          $ip = substr($ipseg, strrpos($ipseg, ":")+1, strlen($ipseg) - (strrpos($ipseg, ":") + 1));
        }else{
          $ipseg = $words[14];


          if(strpos($ipseg, ":")){
            $ip = substr($ipseg, strrpos($ipseg, ":")+1, strlen($ipseg) - (strrpos($ipseg, ":") + 1));
          }else{
            $ip = substr($ipseg, strpos($ipseg,"=")+1, strlen($ipseg) - (strpos($ipseg, "=")+1));
          }


        }

        $this->IP = $ip;
        $this->SERVICE = "telnet";

        $this->ATTEMPTS = 1;
        $this->LASTOFFENCETIMES[] = $this->createDateFromEntry($logEntry);

    }

    /**
     * createFromSecureLog parses the logEntry based on teh assumption that it is from the /var/log/secure file
     * @param $logEntry String - the log entry from the /var/log/secure file to be parsed into a Record object
     * @throws Exception - This method is not implemented due to inability to parse these kinds of log files
     */
    private final function createFromSecureLog($logEntry){
        //Feb 27 11:43:21 ironhide login: FAILED LOGIN 1 FROM localhost.localdomain FOR bensoer, Authentication failure



        throw new Exception("TelnetRecord:createFromSecureLog - Not Implemented");
    }
}
