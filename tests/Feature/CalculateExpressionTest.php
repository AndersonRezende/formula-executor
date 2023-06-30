<?php


use Andersonrezende\FormulaExecutor\FormulaExecutor;
use Andersonrezende\FormulaExecutor\Exception\IncorrectTokenException;
use Andersonrezende\FormulaExecutor\Exception\OperationException;
use PHPUnit\Framework\TestCase;

class CalculateExpressionTest extends TestCase
{

    public function testCalculateExpression()
    {
        $formula = '(a * (b + c) / d - e)';
        $values = array('a' => 5, 'b' => 3, 'c' => 2, 'd' => 4, 'e' => 6);
        $formulaExecutor = new FormulaExecutor();
        $resultFormula = $formulaExecutor->execute($formula, $values);
        expect($resultFormula)->toBe(0.25);
    }

    public function testCalculateExpressionWithConstantsAndVariables()
    {
        $formula = 'm * 9.8';
        $values = array('m' => 10);
        $formulaExecutor = new FormulaExecutor();
        $resultFormula = $formulaExecutor->execute($formula, $values);
        expect($resultFormula)->toBe(98.0);
    }

    public function testCalculateExpressionFull()
    {
        $formula = '(a * (b + c) / d - e ^ f - g)';
        $values = array('a' => 5, 'b' => 3, 'c' => 2, 'd' => 4, 'e' => 6, 'f' => 1, 'g' => 0);
        $formulaExecutor = new FormulaExecutor();
        $resultFormula = $formulaExecutor->execute($formula, $values);
        expect($resultFormula)->toBe(0.25);
    }

    public function testWrongTokens()
    {
        $this->expectException(OperationException::class);
        $formula = 'a + b * c';
        $values = array('a' => 'a', 'b' => 2, 'c' => 3);
        $formulaExecutor = new FormulaExecutor();
        $formulaExecutor->execute($formula, $values);
    }

    public function testTokenNotFound()
    {
        $this->expectException(IncorrectTokenException::class);
        $formula = 'pi * r ^ 2';
        $values = array('r' => 10);
        $formulaExecutor = new FormulaExecutor();
        $formulaExecutor->execute($formula, $values);
    }

    public function testExponentiation()
    {
        $formula = '25^(1/2)';
        $values = array();
        $formulaExecutor = new FormulaExecutor();
        $resultFormula = (int) $formulaExecutor->execute($formula, $values);
        expect($resultFormula)->toBe(5);
    }

}