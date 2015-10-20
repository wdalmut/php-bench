<?php
namespace Bench;

use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Output\OutputInterface;

class BenchRunner
{
    private $finder;
    private $output;

    public function __construct(Finder $finder, OutputInterface $output)
    {
        $this->finder = $finder;
        $this->output = $output;
    }

    public function runBenchmarks()
    {
        $classes = $this->getAvailableClasses();

        foreach ($classes as $class) {
            $reflectedClass = new ReflectionClass($class);

            $methods = $reflectedClass->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                $name = $method->getName();

                if (strpos($name, "benchmark") === 0) {
                    $instance = new $class;

                    $start = microtime(true);
                    call_user_func([$instance, $name], []);
                    $end = microtime(true);

                    $duration = $end-$start;

                    $times = 1;
                    while ($duration < 2) {
                        $times *= 2;
                        $duration = ($end-$start) * $times;
                    }

                    $mean = ($end-$start);
                    for ($i=0; $i<$times; $i++) {
                        $start = microtime(true);
                        call_user_func([$instance, $name], []);
                        $end = microtime(true);

                        $mean += ($end-$start);
                        $mean /= 2;
                    }

                    $this->output->writeln(sprintf("%-59s %10d %s", $class."::".$name, $times, $this->getRescaledTime($mean)));
                }
            }
        }
    }

    private function getRescaledTime($seconds)
    {
        if (($seconds * 1e6) < 1000) {
            return number_format($seconds * 1e6, 3) . " us/op";
        } else if (($seconds * 1e3) < 1000) {
            return number_format($seconds * 1e3, 3) . " ms/op";
        } else {
            return number_format($seconds, 3) . " s/op";
        }
    }

    private function getAvailableClasses()
    {
        $foundClasses = [];
        foreach ($this->finder as $file) {
            $classes = get_declared_classes();
            include_once $file;
            $newClasses = array_diff(get_declared_classes(), $classes);

            if ($newClasses) {
                $foundClasses = array_merge($newClasses, $foundClasses);
            }
        }

        return $foundClasses;
    }
}
