<?php

namespace Script\Engine\Juel;

use El\{
    ValueExpression,
    VariableMapper
};
use Script\ScriptContextInterface;

class ScriptContextVariableMapper extends VariableMapper
{
    private $engine;
    private $scriptContext;

    public function __construct(JuelScriptEngine $engine, ScriptContextInterface $scriptCtx)
    {
        $this->engine = $engine;
        $this->scriptContext = $scriptCtx;
    }

    public function resolveVariable(string $variableName): ?ValueExpression
    {
        $scope = $this->scriptContext->getAttributesScope($variableName);
        if ($scope != -1) {
            $value = $this->scriptContext->getAttribute($variableName, $scope);
            if ($value instanceof ValueExpression) {
                // Just return the existing ValueExpression
                return $value;
            } else {
                // Create a new ValueExpression based on the variable value
                return $this->engine->getExpressionFactory()->createValueExpression(null, null, $value, "object");
            }
        }
        return null;
    }

    public function setVariable(string $variable, ValueExpression $expression): ValueExpression
    {
        $previousValue = $this->resolveVariable($name);
        $this->scriptContext->setAttribute($name, $value, ScriptContextInterface::ENGINE_SCOPE);
        return $previousValue;
    }
}
