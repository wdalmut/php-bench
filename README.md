# Benchmark your class methods or functions

[![Build Status](https://travis-ci.org/wdalmut/php-bench.svg?branch=master)](https://travis-ci.org/wdalmut/php-bench)

Just a simple way to benchmark your class methods or functions.

```sh
./vendor/bin/bench run tests/
```

or with different paths

```sh
./vendor/bin/bench run tests/ benchs/
```

## Declare a bench method

Just create a method in a class starting with the `benchmark` keyword or use a
simple `@benchmark` annotation in your doc-block.

```php
class MyClass
{
    ...

    public function benchmarkUsingTheMethodName($b)
    {
        for ($i=0; $i<$b->times(); $i++) {
            my_project_function("%s", "hello");
        }
    }

    /**
     * @benchmark
     */
    public function this_bench_instead_use_the_annotation($b) {
        for ($i=0; $i<$b->times(); $i++) {
            $myObj->slowMethod("stub");
        }
    }
}
```

The `benchmark` method receive an object `b` from outside that contains the
number of iterations that your bench function should run.

## PHPUnit integration

Just add a `benchmark` method in your testcases.

```php
class MyTest extends \PHPUnit_Framework_TestCase
{
    // Executed only by php-bench
    public function benchmarkMyAppMethod($b)
    {
        for ($i<0; $i<$b->times(); $i++) {
            sprintf("%s", "hello");
        }
    }

    public function testMyAppMethod()
    {
        // ...
        $this->assertEquals(...);
    }
}
```

## Install with composer

You can use composer in order to get this library locally

```json
{
    "require-dev": {
        "wdalmut/php-bench": "*"
    }
}
```

Inspired by Golang benchmark library

