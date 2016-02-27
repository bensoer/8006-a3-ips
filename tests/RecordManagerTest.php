<?php
require_once('../lib/data/RecordManager.php');
require_once('../lib/data/Record.php');
/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 26/02/16
 * Time: 3:19 PM
 */
class RecordManagerTest extends PHPUnit_Framework_TestCase
{

    public function testCreateRecordManager(){

        $recordManager = new RecordManager();

        $this->assertInstanceOf("RecordManager", $recordManager);
    }

    public function testAddRecord(){

        $record = new Record();

        $recordManager = new RecordManager();

        $recordManager->addRecord($record);

        $allRecords = $recordManager->getAllRecords();

        $this->assertNotEmpty($allRecords);
        $this->assertArraySubset(array($record), $allRecords);
    }

    public function testDeleteRecord(){

        $record = new Record();

        $recordManager = new RecordManager();

        $recordManager->addRecord($record);
        $recordManager->deleteRecord($record);

        $allRecords = $recordManager->getAllRecords();

        $this->assertEmpty($allRecords);

    }

    public function testUpdateRecord(){

        $record = new Record();
        $recordManager = new RecordManager();

        $recordManager->addRecord($record);

        $record->BLOCKED = true;
        $record->ATTEMPTS = 6;

        $recordManager->updateRecord($record->IP, $record->SERVICE, $record);

        $allRecords = $recordManager->getAllRecords();

        $this->assertNotEmpty($allRecords);

        $updatedRecord = $allRecords[0];

        $this->assertEquals(true, $updatedRecord->BLOCKED);
        $this->assertEquals(6, $updatedRecord->ATTEMPTS);

    }

    public function testGetRecordIfExists1(){

        $record = new Record();
        $recordManager = new RecordManager();

        $recordManager->addRecord($record);

        $result = $recordManager->getRecordIfExists($record->IP, $record->SERVICE);

        $this->assertInstanceOf("Record", $result);

    }

    public function testGetRecordIfExists2(){

        $record = new Record();
        $recordManager = new RecordManager();

        $recordManager->addRecord($record);

        $result = $recordManager->getRecordIfExists("jdklsa", $record->SERVICE);

        $this->assertNull($result);

    }

    public function testIsOffendingFrequently1(){
        date_default_timezone_set('America/Los_Angeles');

        $record = new Record();

        $record->ATTEMPTS = 3;
        $record->LASTOFFENCETIMES = Array( date_create(), date_create(), date_create());

        $recordManager = new RecordManager();
        $result = $recordManager->isOffendingFrequently($record);

        $this->assertTrue($result);


    }

    public function testIsOffendingFrequently2(){
        date_default_timezone_set('America/Los_Angeles');

        $record = new Record();

        $record->ATTEMPTS = 3;
        $record->LASTOFFENCETIMES = Array( date_create(), date_create(), new DateTime('05/27/2016'));

        $recordManager = new RecordManager();
        $result = $recordManager->isOffendingFrequently($record);

        $this->assertFalse($result);
    }




}
