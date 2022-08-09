<?php

namespace Script\Engine\Lua;

use Script\{
    CompiledScript,
    ScriptContextInterface,
    ScriptEngineInterface
};

class LuaCompiledScript extends CompiledScript
{
    private $script;
    private $engine;

    public function __construct(LuaScriptEngine $engine, string $script)
    {
        $this->engine = $engine;
        $this->script = $script;        
    }

    public function getEngine(): ScriptEngineInterface
    {
        return $engine;
    }

    public function evalContext(ScriptContextInterface $context)
    {
        $lua = new \Lua();

        $bindings = $context->getBindings(ScriptContextInterface::ENGINE_SCOPE);
        $entries = $bindings->entrySet();
        foreach ($entries as $key => $value) {
            $lua->assign($key, $value);
        }

        return $lua->eval($this->script);
    }
}
