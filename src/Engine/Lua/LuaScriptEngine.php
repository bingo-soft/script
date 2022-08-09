<?php

namespace Script\Engine\Lua;

use El\{
    ArrayELResolver,
    ObjectELResolver,
    CompositeELResolver,
    ELContext,
    ELException,
    ELResolver,
    ExpressionFactory,
    FunctionMapper,
    ListELResolver,
    MapELResolver,
    ValueExpression,
    VariableMapper
};
use Script\{
    AbstractScriptEngine,
    BindingsInterface,
    CompilableInterface,
    CompiledScript,
    ScriptContextInterface,
    ScriptEngineInterface,
    ScriptEngineFactoryInterface,
    ScriptException,
    SimpleBindings,
    SimpleScriptContext
};

class LuaScriptEngine extends AbstractScriptEngine implements ScriptEngineInterface, CompilableInterface
{
    private $scriptEngineFactory;

    public function __construct(ScriptEngineFactoryInterface $scriptEngineFactory = null)
    {
        parent::__construct();
        $this->scriptEngineFactory = $scriptEngineFactory ?? new LuaScriptEngineFactory();        
    }

    public function getFactory(): ScriptEngineFactoryInterface
    {
		return $this->scriptEngineFactory;
    }
    
    public function createBindings(): BindingsInterface
    {
        return new SimpleBindings();
    }

    public function eval(string $script, $scriptContextOrBindings = null)
    {
        if ($scriptContextOrBindings instanceof BindingsInterface) {
            $scriptContext = $this->getScriptContext($scriptContextOrBindings);
        } elseif ($scriptContextOrBindings instanceof ScriptContextInterface) {
            $scriptContext = $scriptContext;
        } else {
            $scriptContext = $this->context;
        }
        //$expr = $this->parse($script, $scriptContext);
        return $this->compile($script)->evalContext($scriptContext);
    }

    public function compile(string $script): CompiledScript
    {
        return new LuaCompiledScript($this, $script);
    }
}
