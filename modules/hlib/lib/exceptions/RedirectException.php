<?php

namespace app\modules\hlib\lib\exceptions;

use Exception;
use Throwable;


/**
 * Class RedirectException
 * @package app\modules\hlib\lib
 */
class RedirectException extends Exception
{
    public $route;

    /**
     * RedirectException constructor.
     * @param $route
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($route, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->route = $route;
        parent::__construct($message, $code, $previous);
    }

}