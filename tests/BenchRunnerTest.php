<?php
namespace Bench;

use ArrayIterator;
use PHPUnit_Framework_TestCase;
use Prophecy\Argument;

class BenchRunnerTest extends PHPUnit_Framework_TestCase
{
    public function testRunBenchmarks()
    {
        $strategy = $this->prophesize("Bench\IterationStrategy");
        $strategy->getIterateCount(Argument::Any(),Argument::Any())->willReturn(10);

        $extractor = $this->prophesize("Bench\MethodExtractor");
        $extractor->getCallables()->willReturn([
            ["test", function() {}],
        ]);

        $output = $this->prophesize('Symfony\Component\Console\Output\OutputInterface');
        $output->writeln(Argument::Any())->shouldBeCalledTimes(1);

        $sut = new BenchRunner($extractor->reveal(), $strategy->reveal(), $output->reveal());
        $sut->runBenchmarks(new PrintResult());
    }
}
