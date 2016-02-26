<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 24/02/16
 * Time: 5:50 PM
 */
class NetFilterManager
{

    private function generateBlockingRule($protocol, $ip){
        $rule = "echo 'password' | sudo -S iptables -I INPUT 1 -p $protocol -s $ip -j DROP";

        return $rule;
    }

    private function generateUnBlockingRule($protocol, $ip){
        $rule = "echo 'password' | sudo -S iptables -D INPUT -p $protocol -s $ip -j DROP";

        return $rule;
    }

    public function block($protocol, $ip){
        $rule = $this->generateBlockingRule($protocol, $ip);

        exec($rule);
    }

    public function unblock($protocol, $ip){
        $rule = $this->generateBlockingRule($protocol, $ip);

        exec($rule);
    }
}