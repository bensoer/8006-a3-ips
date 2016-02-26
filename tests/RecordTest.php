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

    public function testCreateRecord(){

        $record = new Record();

        $this->assertInstanceOf("Record", $record);
    }

    public function testDefaultAttributes(){

        $record = new Record();

        $this->assertEquals($record->BLOCKED, false);
        $this->assertEquals($record->IP, null);
        $this->assertEquals($record->ATTEMPTS, 1);
        $this->assertEmpty($record->LASTOFFENCETIMES);

    }

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
