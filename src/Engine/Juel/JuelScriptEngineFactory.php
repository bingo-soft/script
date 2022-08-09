<?php

namespace Script\Engine\Juel;

use Script\{
    ScriptEngineInterface,
    ScriptEngineFactoryInterface
};

class JuelScriptEngineFactory implements ScriptEngineFactoryInterface
{
    public const NAMES = [ "juel" ];
    private const EXTENSIONS = self::NAMES;
    private const MIMETYPES = [];
  
    public function getEngineName(): string
    {
        return "juel";
    }
  
    public function getEngineVersion(): string
    {
        return "1.0";
    }
  
    public function getExtensions(): array
    {
        return self::EXTENSIONS;
    }
  
    public function getLanguageName(): string
    {
        return "2.1 EL";
    }
  
    public function getLanguageVersion(): string
    {
        return "2.1";
    }
  
    public function getMethodCallSyntax(string $obj, string $m, ...$args): string
    {
        throw new \Exception("Method getMethodCallSyntax is not supported");
    }
  
    public function getMimeTypes(): array
    {
        return self::MIMETYPES;
    }
  
    public function getNames(): array
    {
        return self::NAMES;
    }
  
    public function getOutputStatement(string $toDisplay): string
    {
        // We will use out:print function to output statements
        $stringBuffer = "";
        $stringBuffer .= "out:print(\"";
        
        $length = strlen($toDisplay);
        for ($i = 0; $i < $length; $i += 1) {
            $c = $toDisplay[$i];
            switch ($c) {
                case '"':
                    $stringBuffer .= "\\\"";
                    break;
                case '\\':
                    $stringBuffer .= "\\\\";
                    break;
                default:
                    $stringBuffer .= $c;
                    break;
            }
        }
        $stringBuffer .= "\")";
        return $stringBuffer;
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
        // Each statement is wrapped in '${}' to comply with EL
        $buf = "";
        if (count($statements) !== 0) {
            for ($i = 0; $i < count($statements); $i += 1) {
                $buf .= "${";
                $buf .= $statements[$i];
                $buf .= "} ";
            }
        }
        return $buf;
    }
  
    public function getScriptEngine(): ScriptEngineInterface
    {
        return new JuelScriptEngine($this);
    }  
}
