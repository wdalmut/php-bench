<?php
namespace Bench;

use PHPUnit_Framework_TestCase;

class BTest extends PHPUnit_Framework_TestCase
{
    public function testTimeGen()
    {
        $b = new B("test");

        for ($i=0; $i<$b->times(); $i++) {}

        $this->assertNotEquals(0, $b->getDuration());
        $this->assertNotEquals(0, $b->getTimes());
        $this->assertEquals("test", $b->getFunctionName());
    }
}
