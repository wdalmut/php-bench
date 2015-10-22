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

    public function testCalibration()
    {
        $b = new B("test");

        $b->times();
        usleep(1e3);
        $b->times();

        $actualDuration = $b->getDuration();

        $calibration = new B("Calibration");
        $calibration->times();
        usleep(1e2);
        $calibration->times();

        $b->calibrateWith($calibration);

        $this->assertLessThan($actualDuration, $b->getDuration());
    }
}
