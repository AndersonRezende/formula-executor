# formula-executor ![Tests](https://github.com/AndersonRezende/formula-executor/actions/workflows/php.yml/badge.svg)
Simple math expression calculator

## Install:
```
$ composer require nxp/math-executor
```

## Support:
* Multiplication
* Division
* Addition
* Subtraction
* Exponentiation
* Parentheses

## Basic usage:
```php
use Andersonrezende\FormulaExecutor\FormulaExecutor;

$formula = '(a * (b + c) / d - e)';
$values = array('a' => 5, 'b' => 3, 'c' => 2, 'd' => 4, 'e' => 6);
$formulaExecutor = new FormulaExecutor($formula, $values);
$resultFormula = $formulaExecutor->execute();
```

