<?php

namespace Script\Engine\Juel;

use El\ValueExpression;
use Script\{
    CompiledScript,
    ScriptContextInterface,
    ScriptEngineInterface
};

class JuelCompiledScript extends CompiledScript
{
    private $valueExpression;
    private $engine;

    public function __construct(JuelScriptEngine $engine, ValueExpression $valueExpression)
    {
        $this->engine = $engine;
        $this->valueExpression = $valueExpression;        
    }

    public function getEngine(): ScriptEngineInterface
    {
        // Return outer class instance
        return $engine;
    }

    public function eval(?BindingsInterface $ctx = null)
    {
        return $this->scope->evaluateExpression($this->valueExpression, $ctx);
    }
}
