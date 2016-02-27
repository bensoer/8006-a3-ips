<?php
require_once('../lib/data/Settings.php');
/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 26/02/16
 * Time: 3:19 PM
 */
class SettingsTest extends PHPUnit_Framework_TestCase
{

    /**
     * S_UT1
     */
    public function testDefaultAttributes(){

        $settings = new Settings();

        $this->assertEquals(-1, $settings->timeLimit);
        $this->assertEquals(3, $settings->attemptLimit);
        $this->assertEquals("/var/log/secure", $settings->logDir);
        $this->assertNull($settings->lastLogTime);
    }

    /**
     * S_UT2
     */
    public function testCreateSettings(){

        $settings = new Settings();

        $this->assertInstanceOf("Settings", $settings);
    }

    /**
     * S_UT3
     */
    public function testChangingSettings(){
        date_default_timezone_set('America/Los_Angeles');

        $settings = new Settings();

        $settings->timeLimit = 3;
        $settings->attemptLimit = 50;
        $settings->logDir = "/some/other/dir";
        $settings->lastLogTime = date_create();

        $this->assertEquals(3, $settings->timeLimit);
        $this->assertEquals(50, $settings->attemptLimit);
        $this->assertEquals("/some/other/dir", $settings->logDir);
        $this->assertNotNull($settings->lastLogTime);

    }
}
