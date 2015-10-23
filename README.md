# Benchmark your class methods or functions

[![Build Status](https://travis-ci.org/wdalmut/php-bench.svg?branch=master)](https://travis-ci.org/wdalmut/php-bench)

Just a simple way to benchmark your class methods or functions.

```sh
./vendor/bin/bench run tests/
```

Install the bench library

```json
{
    "require-dev": {
        "wdalmut/php-bench": "*"
    }
}
```

Inspired by Golang benchmark library

## Declare a bench method

Just create a method starting with the `benchmark` keyword:

```php
// Somewhere in your code
public function benchmarkCaseOne($b)
{
    for ($i=0; $i<$b->times(); $i++) {
        sprintf("%s", "hello");
    }
}
```

The `benchmark` method receive an object `b` from outside that contains the
number of iterations that your bench function should run.

## PHPUnit integration

Just add a `benchmark*` method in your `TestCase` class

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

