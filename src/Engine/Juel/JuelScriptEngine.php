<?php

namespace Script\Engine\Juel;

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
use Juel\SimpleResolver;
use Script\{
    AbstractScriptEngine,
    BindingsInterface,
    ScriptContextInterface,
    ScriptEngineInterface,
    ScriptEngineFactoryInterface,
    ScriptException,
    SimpleBindings,
    SimpleScriptContext
};
use Util\Reflection\MetaObject;

class JuelScriptEngine extends AbstractScriptEngine
{
    private $scriptEngineFactory;
    private $expressionFactory;

    public function __construct(ScriptEngineFactoryInterface $scriptEngineFactory = null)
    {
        parent::__construct();
        $this->scriptEngineFactory = $scriptEngineFactory;
        $this->expressionFactory = ExpressionFactoryResolver::resolveExpressionFactory();
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
        $expr = $this->parse($script, $scriptContext);
        return $this->evaluateExpression($expr, $scriptContext);
    }

    public function getFactory(): ScriptEngineFactoryInterface
    {
        if ($this->scriptEngineFactory === null) {
            $this->scriptEngineFactory = new JuelScriptEngineFactory();
        }
        return $this->scriptEngineFactory;
    }

    public function getExpressionFactory(): ExpressionFactory
    {
        return $this->expressionFactory;
    }

    public function createBindings(): BindingsInterface
    {
        return new SimpleBindings();
    }

    public function put(string $key, $value): void
    {
        parent::put($key, $value);
        if ($value instanceof MetaObject) {
            $context = $this->createElContext($this->context);
            $context->getELResolver()->setValue($context, null, $key, $value);
        }
    }

    public function evaluateExpression(ValueExpression $expr, ScriptContextInterface $ctx)
    {
        try {
            $context = $this->createElContext($ctx);
            return $expr->getValue($context);
        } catch (ELException $elexp) {
            throw new ScriptException($elexp);
        }
    }

    public function createElResolver(): ELResolver
    {
        $compositeResolver = new CompositeELResolver();
        $compositeResolver->add(new ArrayELResolver());
        $compositeResolver->add(new ListELResolver());
        $compositeResolver->add(new MapELResolver());
        $compositeResolver->add(new ObjectELResolver());
        return new SimpleResolver($compositeResolver);
    }

    private function parse(string $script, ScriptContextInterface $scriptContext): ValueExpression
    {
        try {
            return $this->expressionFactory->createValueExpression($this->createElContext($scriptContext), $script, null, "object");
        } catch (ELException $ele) {
            throw new ScriptException($ele);
        }
    }

    private function createElContext(ScriptContextInterface $scriptCtx): ELContext
    {
        // Check if the ELContext is already stored on the ScriptContext
        $existingELCtx = $scriptCtx->getAttribute("elcontext");
        if ($existingELCtx instanceof ELContext) {
            return $existingELCtx;
        }

        $scriptCtx->setAttribute("context", $scriptCtx, ScriptContextInterface::ENGINE_SCOPE);

        // Built-in function are added to ScriptCtx
        // $scriptCtx->setAttribute("out:print", $this->getPrintMethod(), ScriptContextInterface::ENGINE_SCOPE);
        // $scriptCtx->setAttribute("lang:import", $this->getImportMethod(), ScriptContextInterface::ENGINE_SCOPE);

        $elContext = new class ($this, $scriptCtx) extends ELContext {

            private $resolver;
            private $varMapper;
            private $funcMapper;

            public function __construct(ScriptEngineInterface $engine, ScriptContextInterface $scriptCtx)
            {
                $this->resolver = $engine->createElResolver();
                $this->varMapper = new ScriptContextVariableMapper($engine, $scriptCtx);
                $this->funcMapper = new ScriptContextFunctionMapper($scriptCtx);
            }

            public function getELResolver(): ?ELResolver
            {
                return $this->resolver;
            }

            public function getVariableMapper(): ?VariableMapper
            {
                return $this->varMapper;
            }

            public function getFunctionMapper(): ?FunctionMapper
            {
                return $this->funcMapper;
            }
        };
        // Store the elcontext in the scriptContext to be able to reuse
        $scriptCtx->setAttribute("elcontext", $elContext, ScriptContextInterface::ENGINE_SCOPE);
        return $elContext;
    }
}
