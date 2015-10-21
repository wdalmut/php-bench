<?php
namespace Bench;

class PrintResult
{
    private $b;

    public function __construct(B $b = null)
    {
        $this->b = $b;
    }

    public function withB($b)
    {
        return new PrintResult($b);
    }

    public function __toString()
    {
        return sprintf("%-59s %10d %s", $this->b->getFunctionName(), $this->b->getTimes(), $this->b);
    }
}
