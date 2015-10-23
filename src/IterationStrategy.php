<?php
namespace Bench;

class IterationStrategy
{
    const LOWER_BOUND = 10;

    public function getIterateCount($start, $end)
    {
        $times = 1;
        $duration = $end - $start;
        while ($duration < 2) {
            $times *= 2;
            $duration = ($end-$start) * $times;
        }

        if ($times < self::LOWER_BOUND) {
            $times = self::LOWER_BOUND;
        }

        return $times;
    }
}
