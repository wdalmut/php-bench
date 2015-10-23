<?php
namespace Bench;

class IterationStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testIterateCount()
    {
        $iteration = new IterationStrategy();
        $this->assertEquals(2048, $iteration->getIterateCount(0, 1e-3));
    }

    public function testRiseTestIterationUpToALowerBound()
    {
        $iteration = new IterationStrategy();
        $this->assertEquals(IterationStrategy::LOWER_BOUND, $iteration->getIterateCount(0, 1));
    }
}
