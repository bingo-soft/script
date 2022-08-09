<?php

namespace Script\Engine\Lua;

use Script\{
    ScriptEngineInterface,
    ScriptEngineFactoryInterface
};

class LuaScriptEngineFactory implements ScriptEngineFactoryInterface
{    
    public const NAMES = [ "lua" ];
    private const EXTENSIONS = [ "lua", ".lua" ];
    private const MIMETYPES = ["text/lua", "application/lua"];
    
    public function getEngineName(): string
    {
        return "lua";
    }
    
    public function getEngineVersion(): string
    {
        return "1.0";
    }
    
    public function getExtensions(): array
    {
        return self::EXTENSIONS;
    }
    
    public function getMimeTypes(): array
    {
        return self::MIMETYPES;
    }
    
    public function getNames(): array
    {
        return self::NAMES;
    }
    
    public function getLanguageName(): string
    {
        return "1.0 Lua";
    }
    
    public function getLanguageVersion(): string
    {
        return "1.0";
    }
    
    public function getMethodCallSyntax(string $obj, string $m, ...$args): string
    {
        $sb = "";
        $sb .= $obj . ":" . $m . "(";
        $len = count($args);
        for ($i = 0; $i < $len; $i += 1) {
            if ($i > 0) {
                $sb .= ',';
            }
            $sb .= $args[$i];
        }
        $sb .= ")";
        return $sb;
    }
    
    public function getOutputStatement(string $toDisplay): string
    {
        return "print(" . $toDisplay . ")";
    }

    public function getParameter(string $key)
    {
        if ($key == ScriptEngineInterface::NAME) {
            return $this->getLanguageName();
        } elseif ($key == ScriptEngineInterface::ENGINE) {
            return $this->getEngineName();
        } elseif ($key == ScriptEngineInterface::ENGINE_VERSION) {
            return $this->getEngineVersion();
        } elseif ($key == ScriptEngineInterface::LANGUAGE) {
            return $this->getLanguageName();
        } elseif ($key == ScriptEngineInterface::LANGUAGE_VERSION) {
            return $this->getLanguageVersion();
        } elseif ($key == "THREADING") {
            return "MULTITHREADED";
        } else {
            return null;
        }
    }
    
    public function getProgram(...$statements): string
    {
        $sb = "";
        $len = count($statements);
        for ($i = 0; $i < $len; $i += 1) {
            if ($i > 0) {
                $sb .= '\n';
            }
            $sb .= $statements[$i];
        }
        return $sb;
    }
    
    public function getScriptEngine(): ScriptEngineInterface
    {
        return new LuaScriptEngine();
    }
}