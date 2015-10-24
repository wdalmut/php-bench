<?php
namespace Bench;

use ArrayIterator;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class RunnerCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testBenchApplications()
    {
        $finder = $this->prophesize('Symfony\Component\Finder\Finder');
        $iterator = new ArrayIterator([__DIR__ . '/test.php']);
        $finder->getIterator()->willReturn($iterator);
        $finder->files()->willReturn($finder->reveal());
        $finder->in(__DIR__)->willReturn(true);

        $application = new Application();
        $application->add(new RunnerCommand($finder->reveal()));

        $command = $application->find('run');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'paths' => [__DIR__],
            "--min-duration" => 0.001,
        ]);

        $this->assertRegExp('/^Sut::/i', $commandTester->getDisplay());
    }
}
