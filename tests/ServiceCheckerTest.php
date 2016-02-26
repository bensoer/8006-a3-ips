<?php
require_once("../lib/tools/ServiceChecker.php");
/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 26/02/16
 * Time: 3:07 PM
 */
class ServiceCheckerTest extends PHPUnit_Framework_TestCase
{

    private $validString1 = "Feb 25 17:30:42 ironhide sshd[4963]: Failed password for bensoer from 127.0.0.1 port 55250 ssh2";
    private $validString2 = "Feb 25 17:30:51 ironhide sshd[4963]: PAM 2 more authentication failures; logname= uid=0 euid=0 tty=ssh ruser= rhost=127.0.0.1  user=bensoer";
    private $invalidString1 = "jdslkajdklsajkdjskaklsa  jdslka jk ldsaj lajldska";
    private $invalidString2 = "Feb 25 17:30:51 ironhide jkdlsa[4963]: PAM 2 more authentication failures; logname= uid=0 euid=0 tty=ssh ruser= rhost=127.0.0.1  user=bensoer";

    public function testPosotiveSSHD(){

        $result1 = ServiceChecker::sshd($this->validString1);
        $result2 = ServiceChecker::sshd($this->validString2);

        $this->assertEquals(true, $result1);
        $this->assertEquals(true, $result2);

    }

    public function testNegativeSSHD(){

        $result1 = ServiceChecker::sshd($this->invalidString1);
        $result2 = ServiceChecker::sshd($this->invalidString2);

        $this->assertEquals(false, $result1);
        $this->assertEquals(false, $result2);
    }
}
