<?php

namespace Andersonrezende\FormulaExecutor\Classes;

class Node
{
    public $element;
    public $next;

    /**
     * @param $element
     * @param $next
     */
    public function __construct($element, $next)
    {
        $this->element = $element;
        $this->next = $next;
    }
}