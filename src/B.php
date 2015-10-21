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

    public function __construct($functionName)
    {
        $this->functionName = $functionName;

        $this->duration = 0;
        $this->count = 0;
        $this->times =  2;

        $this->start = 0;
        $this->end = 0;
    }

    public function getFunctionName()
    {
        return $this->functionName;
    }

    public function getTimes()
    {
        return $this->times - 2;
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
            $times = 1;
            $duration = $this->duration;
            while ($duration < 2) {
                $times *= 2;
                $duration = ($this->end-$this->start) * $times;
            }

            $this->times = $times;
        }

        return $this->times;
    }

    public function __toString()
    {
        if (($this->getDuration() * 1e6) < 1000) {
            return (string)number_format($this->getDuration() * 1e6, 3) . " us/op";
        } else if (($this->getDuration() * 1e3) < 1000) {
            return (string)number_format($this->getDuration() * 1e3, 3) . " ms/op";
        } else {
            return (string)number_format($this->getDuration(), 3) . " s/op";
        }

        return "nd";
    }
}
