<?php

namespace Script;

interface ScriptEngineResolverInterface
{
    public function addScriptEngineFactory(ScriptEngineFactoryInterface $scriptEngineFactory): void;

    public function getScriptEngineManager(): ScriptEngineManager;

    /**
     * Returns a cached script engine or creates a new script engine if no such engine is currently cached.
     */
    public function getScriptEngine(string $language, bool $resolveFromCache = false): ?ScriptEngineInterface;
}
