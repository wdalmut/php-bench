<?php
namespace Bench;

use PHPUnit_Framework_TestCase;
use Prophecy\Argument;

class BTest extends PHPUnit_Framework_TestCase
{
    public function testTimeGen()
    {
        $strategy = $this->prophesize("Bench\IterationStrategy");
        $strategy->getIterateCount(Argument::Any(),Argument::Any())->willReturn(10);
        $b = new B("test", $strategy->reveal());

        for ($i=0; $i<$b->times(); $i++) {}

        $this->assertEquals(10, $b->getTimes());
        $this->assertNotEquals(0, $b->getDuration());
        $this->assertEquals("test", $b->getFunctionName());
    }

    public function testCalibration()
    {
        $strategy = $this->prophesize("Bench\IterationStrategy");
        $strategy->getIterateCount(Argument::Any(),Argument::Any())->willReturn(10);
        $b = new B("test", $strategy->reveal());

        $b->times();
        usleep(1e3);
        $b->times();

        $actualDuration = $b->getDuration();

        $calibration = new B("Calibration", $strategy->reveal());
        $calibration->times();
        usleep(1e2);
        $calibration->times();

        $b->calibrateWith($calibration);

        $this->assertLessThan($actualDuration, $b->getDuration());
    }
}
