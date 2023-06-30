<?php

namespace Andersonrezende\FormulaExecutor\Classes;

use Andersonrezende\FormulaExecutor\Exception\MalformedExpressionException;

class InfixToPostfix
{
    private string $infix;
    private string $postfix;
    private array $tokenizedInfix = array();
    private array $tokenizedPostfix = array();
    private array $operators = array('+','-','*','/','^');
    private array $parentheses = array('(',')');
    private array $operatorsAndParentheses = array('+','-','*','/','^', '(', ')');

    /**
     * @throws MalformedExpressionException
     */
    public function __construct($expression)
    {
        $this->infix = $this->removeWhitespace($expression);
        $this->postfix = '';
        $this->tokenize();
        $this->validateTokenizedExpression();
    }


    /**
     * @return void
     */
    public function convert(): void
    {
        $stack = new Stack();
        foreach ($this->tokenizedInfix as $token) {
            switch ($token) {
                case '+':
                case '-':
                case '*':
                case '/':
                case '^':
                while (!$stack->isEmpty() && $this->priority($token) <= $this->priority($stack->peek())) {
                    $this->postfix .= $stack->peek();
                    $this->tokenizedPostfix[] = $stack->peek();
                    $stack->pop();
                }
                $stack->push($token);
                break;
                case '(':
                    $stack->push($token);
                    break;
                case ')':
                    while ($stack->peek() != '(') {
                        $this->postfix .= $stack->peek();
                        $this->tokenizedPostfix[] = $stack->peek();
                        $stack->pop();
                    }
                    if ($stack->peek() == '(') {
                        $stack->pop();
                    }
                    break;
                default:
                    $this->postfix .= $token;
                    $this->tokenizedPostfix[] = $token;
                    break;
            }
        }
        while (!$stack->isEmpty()) {
            if ($stack->peek() != '(') {
                $this->postfix .= $stack->peek();
                $this->tokenizedPostfix[] = $stack->peek();
            }
            $stack->pop();
        }
    }


    /**
     * @return void
     */
    private function tokenize(): void
    {
        $token = '';
        for ($char = 0; $char < strlen($this->infix); $char++) {
            $token .= $this->infix[$char];
            if (($char + 1) < strlen($this->infix)) {
                if ($this->isOperatorOrParenthesis($this->infix[$char])
                    || $this->isOperatorOrParenthesis($this->infix[$char + 1])) {
                    $this->tokenizedInfix[] = $token;
                    $token = '';
                }
            } else {
                $this->tokenizedInfix[] = $token;
                $token = '';
            }
        }
    }

    /**
     * @throws MalformedExpressionException
     */
    private function validateTokenizedExpression(): void
    {
        $tokenizedInfixSize = count($this->tokenizedInfix);
        if ($tokenizedInfixSize < 3) {
            throw new MalformedExpressionException(
                "The expression does not contain a valid minimum number of operands and operators.");
        } else {
            if($this->isOperator($this->tokenizedInfix[0])
                || $this->isOperator($this->tokenizedInfix[$tokenizedInfixSize - 1])
                || $this->tokenizedInfix[0] == ')'
                || $this->tokenizedInfix[$tokenizedInfixSize - 1] == '(') {
                throw new MalformedExpressionException(
                    "Expression does not start or end with a valid symbol.");
            } else {
                for ($index = 0; $index < $tokenizedInfixSize - 1; $index++) {
                    if (!$this->isValidOrder($this->tokenizedInfix[$index], $this->tokenizedInfix[$index + 1])) {
                        $value1 = $this->tokenizedInfix[$index];
                        $value2 = $this->tokenizedInfix[$index + 1];

                        throw new MalformedExpressionException(
                            "The following part of the given expression is incorrect: $value1$value2 .");
                    }
                }
            }
        }
    }

    public function getStringPostfix(): string
    {
        return $this->postfix;
    }

    public function getTokenizedPostfix(): array
    {
        return $this->tokenizedPostfix;
    }


    /**
     * @param $element
     * @return int
     */
    private function priority($element): int
    {
        $priority = 0;
        switch ($element) {
            case '+':
            case '-':
                $priority = 1;
                break;
            case '*':
            case '/':
                $priority = 2;
                break;
            case '^':
                $priority = 3;
                break;
        }
        return $priority;
    }


    /**
     * @param $expression
     * @return string
     */
    private function removeWhitespace($expression): string
    {
        return str_replace(' ', '', $expression);
    }

    /**
     * @param $value
     * @return bool
     */
    private function isOperatorOrParenthesis($value): bool
    {
        return in_array($value, $this->operatorsAndParentheses);
    }

    private function isOperator($value)
    {
        return in_array($value, $this->operators);
    }

    private function isValidOrder($value1, $value2): bool
    {
        if ($value1 == '(' && $this->isOperator($value2)
            || ($this->isOperator($value1) && $this->isOperator($value2))
            || ($this->isOperator($value1) && $value2 == ')')
            || (in_array($value1, $this->parentheses) && in_array($value2, $this->parentheses))) {
            return false;

        }
        return true;
    }
}