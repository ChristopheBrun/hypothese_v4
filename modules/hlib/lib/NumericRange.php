<?php

namespace app\modules\hlib\lib;


/**
 * Class NumericRange
 * @package app\modules\hlib\lib
 */
class NumericRange
{
    /** @var  array */
    private $values;

    /** @var  array */
    private $options;

    /** @var  int */
    protected $from;

    /** @var  int */
    protected $to;

    /**
     * @return array
     */
    public function values()
    {
        if (!isset($this->values)) {
            $this->values = [];
            for ($i = $this->from; $i <= $this->to; ++$i) {
                $this->values[] = $i;
            }
        }

        return $this->values;
    }

    /**
     * @return array
     */
    public function options()
    {
        if (!isset($this->options)) {
            $this->options = [];
            for ($i = $this->from; $i <= $this->to; ++$i) {
                $this->options[$i] = $i;
            }
        }

        return $this->options;
    }
}