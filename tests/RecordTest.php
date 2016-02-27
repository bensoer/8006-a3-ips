<?php
require_once('../lib/data/Record.php');
/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 26/02/16
 * Time: 3:20 PM
 */
class RecordTest extends PHPUnit_Framework_TestCase
{

    /**
     * R_UT1
     */
    public function testCreateRecord(){

        $record = new Record();

        $this->assertInstanceOf("Record", $record);
    }

    /**
     * R_UT2
     */
    public function testDefaultAttributes(){

        $record = new Record();

        $this->assertEquals($record->BLOCKED, false);
        $this->assertEquals($record->IP, null);
        $this->assertEquals($record->ATTEMPTS, 1);
        $this->assertEmpty($record->LASTOFFENCETIMES);

    }

    /**
     * R_UT3
     */
    public function testSettingAttributes(){

        $record = new Record();

        $record->ATTEMPTS += 3;
        $this->assertEquals($record->ATTEMPTS, 4);

        $record->BLOCKED = true;
        $this->assertEquals($record->BLOCKED, true);

        $record->IP = "jdlsal";
        $this->assertNotNull($record->IP);
    }


}
