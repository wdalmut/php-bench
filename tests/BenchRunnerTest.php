<?php
namespace Bench;

use ArrayIterator;
use PHPUnit_Framework_TestCase;
use Prophecy\Argument;

class BenchRunnerTest extends PHPUnit_Framework_TestCase
{
    public function testRunBenchmarks()
    {
        $finder = $this->prophesize("Symfony\Component\Finder\Finder");
        $iterator = new ArrayIterator([__DIR__ . '/test.php']);
        $finder->getIterator()->willReturn($iterator);;

        $output = $this->prophesize('Symfony\Component\Console\Output\OutputInterface');
        $output->writeln(Argument::Any())->shouldBeCalled();

        $sut = new BenchRunner($finder->reveal(), $output->reveal());
        $sut->runBenchmarks(new PrintResult());
    }
}
