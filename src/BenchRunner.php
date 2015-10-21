<?php
namespace Bench;

use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class BenchRunner
{
    private $finder;
    private $output;

    public function __construct(Finder $finder, OutputInterface $output)
    {
        $this->finder = $finder;
        $this->output = $output;
    }

    public function runBenchmarks(PrintResult $printResult)
    {
        $classes = $this->getAvailableClasses();

        foreach ($classes as $class) {
            $reflectedClass = new ReflectionClass($class);

            $methods = $reflectedClass->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                $name = $method->getName();

                if (strpos($name, "benchmark") === 0) {
                    $instance = new $class;

                    $b = new B("{$class}::{$name}");
                    call_user_func([$instance, $name], $b);

                    $this->output->writeln((string)$printResult->withB($b));
                }
            }
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
