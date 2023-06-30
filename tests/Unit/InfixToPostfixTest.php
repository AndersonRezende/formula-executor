<?php

namespace Andersonrezende\Test;

use PHPUnit\Framework\TestCase;
use Andersonrezende\FormulaExecutor\Exception\MalformedExpressionException;
use Andersonrezende\FormulaExecutor\Classes\InfixToPostfix;

class InfixToPostfixTest extends TestCase
{
    public function testConvertInfixToPostfixString(): void
    {
        $infix = 'a * (b + c) * (d - g) * h';
        $itp = new InfixToPostfix($infix);
        $itp->convert();
        expect($itp->getTokenizedPostfix())->toMatchArray([
            "a", "b", "c", "+", "*", "d", "g", "-", "*", "h", "*"
        ]);
    }

    public function testConvertInfixToPostfixTokenized()
    {
        $infix = '(a + b) ^ b * d';
        $itp = new InfixToPostfix($infix);
        $itp->convert();
        expect($itp->getStringPostfix())->toBe('ab+b^d*');
    }

    public function testInvalidInfixExpression(): void
    {
        $this->expectException(MalformedExpressionException::class);
        $infix = 'a * (b + c) * (d - g + ) * h';
        $itp = new InfixToPostfix($infix);
        $itp->convert();
    }

    public function testStartsWithInvalidSymbol(): void
    {
        $this->expectException(MalformedExpressionException::class);
        $infix = ')a * (b + c) * (d - g + ) * h';
        $itp = new InfixToPostfix($infix);
        $itp->convert();
    }

    public function testEndsWithInvalidSymbol(): void
    {
        $this->expectException(MalformedExpressionException::class);
        $infix = 'a * (b + c) * (d - g + ) * h (';
        $itp = new InfixToPostfix($infix);
        $itp->convert();
    }

    public function testInvalidMinimumNumber(): void
    {
        $this->expectException(MalformedExpressionException::class);
        $infix = 'a *';
        $itp = new InfixToPostfix($infix);
        $itp->convert();
    }

    
}