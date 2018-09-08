<?php

namespace app\modules\hlib\lib\ranges;

use app\modules\hlib\lib\NumericRange;


/**
 * Class Years
 * @package app\modules\hlib\lib\ranges
 */
class Years extends NumericRange
{
    /**
     * @param int      $from
     * @param int|null $to
     */
    public function __construct($from = 1900, $to = null)
    {
        $this->from = $from;
        if (is_null($to)) {
            $this->to = date('Y');
        }
    }
}