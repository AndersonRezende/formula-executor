<?php

namespace Andersonrezende\Test;

use Andersonrezende\FormulaExecutor\Classes\Stack;
use PHPUnit\Framework\TestCase;

class StackTest extends TestCase
{
    public function testStack()
    {
        $stack = new Stack();
        //expect($stack->peek())->toBeNull();
        $stack->push(0);
        expect($stack->peek())->toBeInt(0);
        $stack->push(0.0);
        expect($stack->peek())->toBeFloat(0.0);
    }

}