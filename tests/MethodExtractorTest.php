<?php
namespace Bench;

use ArrayIterator;
use ReflectionClass;

class MethodExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtractNothing()
    {
        $iterator = new ArrayIterator([]);

        require __DIR__ . '/test.php';
        $extractor = new MethodExtractor($iterator);
        $class = new ReflectionClass($this);
        $callables = $extractor->getBenchmarksFrom($class->getMethods());

        $this->assertCount(0, $callables);
    }

    public function testExtractBenchmarks()
    {
        require __DIR__ . '/test.php';
        $iterator = new ArrayIterator([]);

        $extractor = new MethodExtractor($iterator);
        $class = new \Sut();

        $class = new ReflectionClass($class);
        $callables = $extractor->getBenchmarksFrom($class->getMethods());

        $this->assertCount(3, $callables);
        $this->assertEquals("benchmarkTestCase", $callables[0]);
        $this->assertEquals("benchmarkTestCaseWithALongerDescription", $callables[1]);
    }

    public function testExtractFromClass()
    {
        $iterator = new ArrayIterator([]);

        require __DIR__ . '/test.php';
        $extractor = new MethodExtractor($iterator);

        $class = new \Sut();
        $callables = $extractor->extractFrom($class);

        $this->assertCount(3, $callables);
        list($key, $callable) = $callables[0];
        $this->assertEquals('Sut::benchmarkTestCase', $key);
        $this->assertInstanceOf("Sut", $callable[0]);
        $this->assertEquals("benchmarkTestCase", $callable[1]);
        list($key, $callable) = $callables[1];
        $this->assertEquals('Sut::benchmarkTestCaseWithALongerDescription', $key);
        $this->assertInstanceOf("Sut", $callable[0]);
        $this->assertEquals("benchmarkTestCaseWithALongerDescription", $callable[1]);
        list($key, $callable) = $callables[2];
        $this->assertEquals('Sut::withDocComment', $key);
        $this->assertInstanceOf("Sut", $callable[0]);
        $this->assertEquals("withDocComment", $callable[1]);
    }

    public function testCallables()
    {
        $iterator = new ArrayIterator([__DIR__ . '/test.php']);

        $extractor = new MethodExtractor($iterator);
        $callables = $extractor->getCallables();

        $this->assertCount(3, $callables);
    }

    public function testSkipNotPHPFiles()
    {
        $iterator = new ArrayIterator([__DIR__ . '/../README.md']);

        $extractor = new MethodExtractor($iterator);
        $callables = $extractor->getCallables();

        $this->assertCount(0, $callables);
    }
}
