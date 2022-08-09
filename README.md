[![Latest Stable Version](https://poser.pugx.org/bingo-soft/script/v/stable.png)](https://packagist.org/packages/bingo-soft/script)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bingo-soft/script/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/bingo-soft/script/?branch=main)

# Scripting API

Scripting API implemented in PHP

# Installation

Install library, using Composer:

```
composer require bingo-soft/script
```

# Example 1

```
$manager = new ScriptEngineManager();
$engine = $manager->getEngineByName("juel");
echo $engine->eval('${1 + 2}'); //prints 3
```

# Example 2

```
$manager = new ScriptEngineManager();
$engine = $manager->getEngineByName("juel");

$simple = new class () {
    public $propFloat = 1.23;

    public function foo(): int
    {
        return 11;
    }

    public function bar(): int
    {
        return 23;
    }
};
$engine->put("simple", $simple);

echo $engine->eval('${simple.propFloat + 2}'); //prints 3.23
echo $engine->eval('${simple.bar() + simple.foo()}'); //prints 34
```

# Running tests

```
./vendor/bin/phpunit ./tests
```
