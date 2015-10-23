<?php
namespace Bench;

use ArrayAccess;

class B
{
    private $duration;
    private $count;
    private $times;

    private $start;
    private $end;

    private $functionName;
    private $strategy;

    public function __construct($functionName, IterationStrategy $strategy)
    {
        $this->functionName = $functionName;

        $this->duration = 0;
        $this->count = 0;
        $this->times =  2;

        $this->start = 0;
        $this->end = 0;

        $this->strategy = $strategy;
    }

    public function getFunctionName()
    {
        return $this->functionName;
    }

    public function getTimes()
    {
        return $this->times;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function times()
    {
        if ($this->count % 2 == 0) {
            $this->start = microtime(true);
        }

        if ($this->count % 2 != 0) {
            $this->end = microtime(true);
            $this->duration += ($this->end - $this->start);

            if ($this->count > 2) {
                $this->duration /= 2;
            }
        }

        ++$this->count;

        if ($this->count == 2) {
            $this->times = $this->strategy->getIterateCount($this->start, $this->end);
        }

        return $this->times;
    }

    public function calibrateWith(B $b)
    {
        $this->duration = $this->duration - $b->getDuration();
        return $this;
    }
}
