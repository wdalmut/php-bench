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

    public function getDurationInScientificNotation($time)
    {
        if (($time * 1e6) < 1000) {
            return (string)number_format($time * 1e6, 3) . " us/op";
        } else if (($time * 1e3) < 1000) {
            return (string)number_format($time * 1e3, 3) . " ms/op";
        } else {
            return (string)number_format($time, 3) . " s/op";
        }

        return "nd";
    }

    public function __toString()
    {
        return sprintf("%-59s %10d %s", $this->b->getFunctionName(), $this->b->getTimes(), $this->getDurationInScientificNotation($this->b->getDuration()));
    }
}
