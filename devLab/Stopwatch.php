<?php

namespace Assist;

/*
 * TODO:
 * add possiblity to switch on/off format execution time (can get raw microtime)
 */

class Stopwatch
{

    /*
     * Get time imprint
     */
    public function getImprint()
    {
       return microtime(true);
    }

    /*
     * Prepare total execution time X-min Y-s Z-ms
     */
    public function getExecutionTime($executionTimeStart, $executionTimeEnd, $format = true)
    {
        $executionTimeResult = - ($executionTimeStart - $executionTimeEnd);

        if ($format)
        {
            return $this->format($executionTimeResult);
        }
        else
        {
            return $executionTimeResult;
        }
    }

    public function format($executionTimeResult)
    {
        $format = new \stdClass();

        $milliseconds = round($executionTimeResult * 1000);
        $total = explode(".", $milliseconds / 1000);
        if (array_key_exists(1, $total)) {
            $ms = $total[1];
        } else {
            $ms = 0;
        }

        if ($total[0] > 60) {
            $min = $total[0] / 60;
            $sec = $total[0] / 60 - $min;
        } else {
            $min = 0;
            $sec = $total[0];
        }

        $format->min = $min;
        $format->sec = $sec;
        $format->ms = $ms;
        $format->unformatted = $executionTimeResult;

        return $format;
    }


}