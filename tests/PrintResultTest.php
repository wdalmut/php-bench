<?php
namespace Bench;

class PrintResultTest extends \PHPUnit_Framework_TestCase
{
    public function testShowThingsUp()
    {
        $b = new B("test");
        $printer = new PrintResult($b);

        $this->assertRegExp("/test[ ]*\d+[ ]*\d+\.\d+ (ms|us|s){1}\/op/i", (string)$printer);
    }
}
