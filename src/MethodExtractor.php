<?php
namespace Bench;

use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Finder\Finder;

class MethodExtractor
{
    private $finder;

    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    public function getCallables()
    {
        $classes = $this->getAvailableClasses();

        $callables = [];
        foreach ($classes as $class) {
            $callables = array_merge($this->extractFrom($class), $callables);
        }

        return $callables;
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

    public function extractFrom($class)
    {
        $reflectedClass = new ReflectionClass($class);

        $methods = $reflectedClass->getMethods(ReflectionMethod::IS_PUBLIC);
        $callables = $this->getBenchmarksFrom($methods);

        array_walk($callables, function(&$value) use ($class, $reflectedClass) {
            $value = [$reflectedClass->getName() . '::' . $value, [$class, $value]];
        });

        return $callables;
    }

    public function getBenchmarksFrom(array $methods)
    {
        $callables = [];

        foreach ($methods as $method) {
            $name = $method->getName();

            if (strpos($name, "benchmark") === 0) {
                $callables[] = $name;
            }
        }

        return $callables;
    }
}
