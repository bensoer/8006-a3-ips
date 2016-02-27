<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 24/02/16
 * Time: 5:50 PM
 */

/**
 * Class NetFilterManager is a helper class that executes commands to iptables in order to enforce blocking and unblocking
 * of offending users
 */
class NetFilterManager
{

    private $sudoPassword;

    public function __construct($sudoPassword){
        $this->sudoPassword = $sudoPassword;
    }

    /**
     * generateBlockRule creates an iptables command that blocks a user matching the passed in protocol and ip
     * @param $protocol String - the protocol being blocked
     * @param $ip String - the ip being blocked
     * @return string - the generated command to execute
     */
    private function generateBlockingRule($protocol, $ip){
        $rule = "echo '$this->sudoPassword' | sudo -S iptables -I INPUT 1 -p $protocol -s $ip -j DROP";

        return $rule;
    }

    /**
     * generateUnBlockRule creates an iptables command to delete a block rule created in the generateBlockingRule method
     * based on the passe din protcol and ip
     * @param $protocol String - the protocol in the rule being blocked
     * @param $ip String - the ip in the rule being blocked
     * @return string - the generated command to execute to delete the block rule generated in generateBlockingRule
     */
    private function generateUnBlockingRule($protocol, $ip){
        $rule = "echo '$this->sudoPassword' | sudo -S iptables -D INPUT -p $protocol -s $ip -j DROP";

        return $rule;
    }

    /**
     * block generates a blocking rules based on the passed in protocol and ip and then executes it on the system
     * @param $protocol String - the protocol to block on
     * @param $ip - the ip to block
     */
    public function block($protocol, $ip){
        $rule = $this->generateBlockingRule($protocol, $ip);

        exec($rule);
    }

    /**
     * unblock generates an unblocking command based on the passed in protocol and ip and then executes it on the system
     * @param $protocol String - the protocol being unblocked
     * @param $ip String - the ip being unblocked
     */
    public function unblock($protocol, $ip){
        $rule = $this->generateUnBlockingRule($protocol, $ip);

        exec($rule);
    }
}