<?php

namespace Andersonrezende\FormulaExecutor\Classes;

class Stack
{
    private $top;
    private $size;

    public function __construct()
    {
        $this->top = null;
        $this->size = 0;
    }

    public function push($element)
    {
        $this->top = new Node($element, $this->top);
        $this->size++;
    }

    public function pop()
    {
        if(!$this->isEmpty()) {
            $temp = $this->top;
            $this->top = $temp->next;
            $this->size--;
        }
    }

    public function peek()
    {
        return !is_null($this->top->element) ? $this->top->element : null;
    }

    public function isEmpty()
    {
        return ($this->size <= 0 || is_null($this->peek()));
    }

}