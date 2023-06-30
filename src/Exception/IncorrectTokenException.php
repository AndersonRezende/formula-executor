<?php

namespace Andersonrezende\FormulaExecutor\Exception;

class IncorrectTokenException extends \Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}