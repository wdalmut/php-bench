<?php
namespace Bench;

class IterationStrategy
{
    private $minDuration;
    private $lowerIterateBound;

    public function __construct($minDuration = 2, $lowerIterateBound = 10)
    {
        $this->minDuration = $minDuration;
        $this->lowerIterateBound = $lowerIterateBound;
    }

    public function getIterateCount($start, $end)
    {
        $times = 1;
        $duration = $end - $start;
        while ($duration < $this->minDuration) {
            $times *= 2;
            $duration = ($end-$start) * $times;
        }

        if ($times < $this->lowerIterateBound) {
            $times = $this->lowerIterateBound;
        }

        return $times;
    }
}
