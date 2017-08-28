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
    public function getExecutionTime($executionTimeStart, $executionTimeEnd)
    {
        $executionTimeResult = - ($executionTimeStart - $executionTimeEnd);

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
        return '<b>' . $min . '</b>min <b>' . $sec . '</b>s <b>' . $ms . '</b>ms';
    }
}