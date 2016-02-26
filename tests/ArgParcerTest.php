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

    public function testFormatArguments(){

        $formattedArray = ArgParcer::formatArguments($this->array);

        $this->assertNotEmpty($formattedArray);
        $this->assertArrayHasKey("-a", $formattedArray);
        $this->assertArrayHasKey("-b", $formattedArray);
    }

    public function testFormatNullArguments(){

        $formattedArray = ArgParcer::formatArguments(Array());

        $this->assertEmpty($formattedArray);
    }

    public function testGetInstance(){

        $formattedArray = ArgParcer::formatArguments($this->array);
        $instance = ArgParcer::getInstance($formattedArray);

        $this->assertInstanceOf("ArgParcer", $instance);
    }

    public function testGetKey(){

        $formattedArray = ArgParcer::formatArguments($this->array);
        $instance = ArgParcer::getInstance($formattedArray);

        $valueA = $instance->getValue("-a");
        $valueB = $instance->getValue("-b");

        $this->assertEquals("value1", $valueA);
        $this->assertEquals("value2", $valueB);
    }
}
