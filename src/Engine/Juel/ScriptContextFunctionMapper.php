<?php

namespace Script\Engine\Juel;

use El\FunctionMapper;
use Script\ScriptContextInterface;

class ScriptContextFunctionMapper extends FunctionMapper
{
    private $scriptContext;

    public function __construct(ScriptContextInterface $ctx)
    {
        $this->scriptContext = $ctx;
    }

    private function getFullFunctionName(string $prefix, string $localName): string
    {
        return $prefix . ":" . $localName;
    }

    public function resolveFunction(string $prefix, string $localName): ?\ReflectionMethod
    {
        $functionName = $this->getFullFunctionName($prefix, $localName);
        $scope = $this->scriptContext->getAttributesScope($functionName);
        if ($scope != -1) {
            // Methods are added as variables in the ScriptScope
            $attributeValue = $this->scriptContext->getAttribute($functionName);
            return ($attributeValue instanceof \ReflectionMethod) ? $attributeValue : null;
        } else {
            return null;
        }
    }
}
