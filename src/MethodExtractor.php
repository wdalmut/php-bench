<?php
namespace Bench;

use Iterator;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Finder\Finder;

class MethodExtractor
{
    private $files;

    public function __construct(Iterator $files)
    {
        $this->files = $files;
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
        foreach ($this->files as $file) {
            $classes = get_declared_classes();
            if (pathinfo($file, PATHINFO_EXTENSION) == "php") {
                include_once $file;
                $newClasses = array_diff(get_declared_classes(), $classes);

                if ($newClasses) {
                    $foundClasses = array_merge($newClasses, $foundClasses);
                }
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
            $value = [$reflectedClass->getName() . '::' . $value, [new $class, $value]];
        });

        return $callables;
    }

    public function getBenchmarksFrom(array $methods)
    {
        $callables = [];

        foreach ($methods as $method) {
            if ($this->isBenchmark($method)) {
                $callables[] = $method->getName();
            }
        }

        return $callables;
    }

    private function isBenchmark(ReflectionMethod $method)
    {
        $isBenchmark = false;
        foreach ([$method->getName(), $method->getDocComment()] as $thing) {
            if (preg_match("/@benchmark[ ]*\n|^benchmark[a-zA-Z0-9]+/i", $thing)) {
                $isBenchmark = true;
            }
        }

        return $isBenchmark;
    }
}
