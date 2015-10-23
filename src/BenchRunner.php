<?php
namespace Bench;

use Bench\Search;
use ReflectionClass;
use ReflectionMethod;
use Bench\IterationStrategy;
use Symfony\Component\Console\Output\OutputInterface;

class BenchRunner
{
    const CALIBRATE = 1000;

    private $extractor;
    private $strategy;
    private $output;

    public function __construct(MethodExtractor $extractor, IterationStrategy $strategy,  OutputInterface $output)
    {
        $this->extractor = $extractor;
        $this->strategy = $strategy;
        $this->output = $output;
    }

    public function runBenchmarks(PrintResult $printResult)
    {
        $callables = $this->extractor->getCallables();

        foreach ($callables as $element) {
            list($name, $callable) = $element;
            $b = new B($name, $this->strategy);
            call_user_func($callable, $b);

            $calibration = $this->getAvgCalibration();
            $b->calibrateWith($calibration);

            $this->output->writeln((string)$printResult->withB($b));
        }
    }

    private function getAvgCalibration()
    {
        $b = new B("calibration", $this->strategy);
        for ($i=0; $i<self::CALIBRATE; $i++) {
            // Calibration stub
        }
        return $b;
    }
}
