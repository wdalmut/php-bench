<?php
namespace Bench;

use Prophecy\Argument;

class PrintResultTest extends \PHPUnit_Framework_TestCase
{
    public function testShowThingsUp()
    {
        $strategy = $this->prophesize("Bench\IterationStrategy");
        $strategy->getIterateCount(Argument::Any(),Argument::Any())->willReturn(10);
        $b = new B("test", $strategy->reveal());
        $printer = new PrintResult($b);

        $this->assertRegExp("/test[ ]*\d+[ ]*\d+\.\d+ (ms|us|s){1}\/op/i", (string)$printer);
    }

    /**
     * @dataProvider getDurations
     */
    public function testScientificNotation($res, $time)
    {
        $result = $this->getMockBuilder('Bench\PrintResult')
                     ->disableOriginalConstructor()
                     ->setMethods(["h725"])
                     ->getMock();
        $this->assertEquals($res, $result->getDurationInScientificNotation($time));
    }

    public function getDurations()
    {
        return [
            ["1.000 us/op", 1e-6],
            ["1.000 ms/op", 1e-3],
            ["1.000 s/op", 1],
            ["1.123 s/op", 1.123],
            ["1.123 us/op", 1.123e-6],
            ["1.000 ms/op", 1000.123e-6],
            ["999.123 us/op", 999.123e-6],
            ["999.123 ms/op", 999.123e-3],
        ];
    }
}

