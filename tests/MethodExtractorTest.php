<?php
namespace Bench;

use ArrayIterator;
use ReflectionClass;

class MethodExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtractNothing()
    {
        $finder = $this->prophesize("Symfony\Component\Finder\Finder");
        $iterator = new ArrayIterator([]);
        $finder->getIterator()->willReturn($iterator);;

        require __DIR__ . '/test.php';
        $extractor = new MethodExtractor($finder->reveal());
        $class = new ReflectionClass($this);
        $callables = $extractor->getBenchmarksFrom($class->getMethods());

        $this->assertCount(0, $callables);
    }

    public function testExtractBenchmarks()
    {
        require __DIR__ . '/test.php';

        $finder = $this->prophesize("Symfony\Component\Finder\Finder");
        $iterator = new ArrayIterator([]);
        $finder->getIterator()->willReturn($iterator);;

        $extractor = new MethodExtractor($finder->reveal());
        $class = new \Sut();

        $class = new ReflectionClass($class);
        $callables = $extractor->getBenchmarksFrom($class->getMethods());

        $this->assertCount(3, $callables);
        $this->assertEquals("benchmarkTestCase", $callables[0]);
        $this->assertEquals("benchmarkTestCaseWithALongerDescription", $callables[1]);
    }

    public function testExtractFromClass()
    {
        $finder = $this->prophesize("Symfony\Component\Finder\Finder");
        $iterator = new ArrayIterator([]);
        $finder->getIterator()->willReturn($iterator);;

        require __DIR__ . '/test.php';
        $extractor = new MethodExtractor($finder->reveal());

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
        $finder = $this->prophesize("Symfony\Component\Finder\Finder");
        $iterator = new ArrayIterator([__DIR__ . '/test.php']);
        $finder->getIterator()->willReturn($iterator);;

        $extractor = new MethodExtractor($finder->reveal());
        $callables = $extractor->getCallables();

        $this->assertCount(3, $callables);
    }
}
