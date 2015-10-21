<?php
class Sut
{
    public function benchmarkTestCase($b)
    {
        $s = new ReflectionClass("Sut");
        for ($i=0; $i<$b->times(); $i++) {
            $s->getMethods();
        }
    }

    public function benchmarkTestCaseWithALongerDescription($b)
    {
        for ($i=0; $i<$b->times(); $i++) {
            usleep(1e3);
        }
    }

    /**
     * @benchmark
     */
    public function something()
    {

    }
}
