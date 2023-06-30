<?php

namespace Andersonrezende\FormulaExecutor;

use Andersonrezende\FormulaExecutor\Classes\InfixToPostfix;
use Andersonrezende\FormulaExecutor\Classes\Stack;
use Andersonrezende\FormulaExecutor\Exception\IncorrectTokenException;
use Andersonrezende\FormulaExecutor\Exception\MalformedExpressionException;
use Andersonrezende\FormulaExecutor\Exception\OperationException;

class FormulaExecutor
{
    private string $expression;
    private array $values;
    private array $operands = array('+','-','*','/','^');


    /**
     * @return float|int|string
     * @throws OperationException
     * @throws IncorrectTokenException
     * @throws MalformedExpressionException
     */
    public function execute($expression, $values): float|int|string
    {
        $this->expression = $expression;
        $this->values = $values;
        $itp = new InfixToPostfix($this->expression);
        $itp->convert();
        $tokenizedExpression = $itp->getTokenizedPostfix();
        $tokenizedExpression = $this->replaceTokensToValues($tokenizedExpression);

        $result = 0;
        $stack = new Stack();
        foreach ($tokenizedExpression as $token) {
            if(in_array($token, $this->operands)) {
                $operand = $token;
                $token2 = $stack->peek();
                $stack->pop();
                $token1 = $stack->peek();
                $stack->pop();
                $result = $this->calculate($token1, $token2, $operand);
                $stack->push($result);
            } else {
                $stack->push($token);
            }
        }
        return $result;

    }

    /**
     * @param $token1
     * @param $token2
     * @param $operand
     * @return float|int|string
     * @throws OperationException
     */
    private function calculate($token1, $token2, $operand): float|int|string
    {
        if (is_numeric($token1) && is_numeric($token2)) {
            $result = 0;
            switch ($operand) {
                case '+':
                    $result = $token1 + $token2;
                    break;
                case '-':
                    $result = $token1 - $token2;
                    break;
                case '*':
                    $result = $token1 * $token2;
                    break;
                case '/':
                    $result = $token1 / $token2;
                    break;
                case '^':
                    $result = $token1 ** $token2;
                    break;
            }
        } else {
            throw new OperationException("The operation must be between two numerical values.
            The reported expression was: $token1 $operand $token2");
        }
        return $result;
    }

    /**
     * @param $tokenizedExpression
     * @return mixed
     * @throws IncorrectTokenException
     */
    private function replaceTokensToValues($tokenizedExpression): array
    {
        foreach ($tokenizedExpression as $key => $value) {
            if (!in_array($value, $this->operands) && !is_numeric($value)) {
                if(array_key_exists($value, $this->values)) {
                    $tokenizedExpression[$key] = $this->values[$value];
                } else {
                    throw new IncorrectTokenException("no value found to replace token $value in expression.");
                }
            }
        }
        return $tokenizedExpression;
    }
}