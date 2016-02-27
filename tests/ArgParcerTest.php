<?php
require_once("../lib/tools/ArgParcer.php");
/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 26/02/16
 * Time: 2:39 PM
 */
class ArgParcerTest extends PHPUnit_Framework_TestCase
{
    private $array = Array("programname", "-a", "value1", "-b", "value2");

    /**
     * AP_UT1
     */
    public function testFormatArguments(){

        $formattedArray = ArgParcer::formatArguments($this->array);

        $this->assertNotEmpty($formattedArray);
        $this->assertArrayHasKey("-a", $formattedArray);
        $this->assertArrayHasKey("-b", $formattedArray);
    }

    /**
     * AP_UT2
     */
    public function testFormatNullArguments(){

        $formattedArray = ArgParcer::formatArguments(Array());

        $this->assertEmpty($formattedArray);
    }

    /**
     * AP_UT3
     */
    public function testGetInstance(){

        $formattedArray = ArgParcer::formatArguments($this->array);
        $instance = ArgParcer::getInstance($formattedArray);

        $this->assertInstanceOf("ArgParcer", $instance);
    }

    /**
     * AP_UT4
     */
    public function testGetKey(){

        $formattedArray = ArgParcer::formatArguments($this->array);
        $instance = ArgParcer::getInstance($formattedArray);

        $valueA = $instance->getValue("-a");
        $valueB = $instance->getValue("-b");

        $this->assertEquals("value1", $valueA);
        $this->assertEquals("value2", $valueB);
    }
}
