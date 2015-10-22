<?php
namespace Bench;

use Bench\Search;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Console\Output\OutputInterface;

class BenchRunner
{
    const CALIBRATE = 1000;

    private $extractor;
    private $output;

    public function __construct(MethodExtractor $extractor, OutputInterface $output)
    {
        $this->extractor = $extractor;
        $this->output = $output;
    }

    public function runBenchmarks(PrintResult $printResult)
    {
        $callables = $this->extractor->getCallables();

        foreach ($callables as $element) {
            list($name, $callable) = $element;
            $b = new B($name);
            call_user_func($callable, $b);

            $calibration = $this->getAvgCalibration();
            $b->calibrateWith($calibration);

            $this->output->writeln((string)$printResult->withB($b));
        }
    }

    private function getAvgCalibration()
    {
        $b = new B("calibration");
        for ($i=0; $i<self::CALIBRATE; $i++) {
            // Calibration stub
        }
        return $b;
    }
}
