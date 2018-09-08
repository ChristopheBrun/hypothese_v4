<?php

namespace app\modules\hlib\lib\ranges;

use app\modules\hlib\lib\NumericRange;

/**
 * Class Days
 * @package app\modules\hlib\lib
 */
class Days extends NumericRange
{
    /**
     * @param int      $from
     * @param int|null $to
     */
    public function __construct($from = 1, $to = 31)
    {
        $this->from = $from;
        $this->to = $to;

    }
}