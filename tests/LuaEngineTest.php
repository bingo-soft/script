<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Script\ScriptEngineManager;
use Script\Engine\Lua\LuaScriptEngine;

class LuaEngineTest extends TestCase
{
    public function testLuaScript(): void
    {
        if (class_exists(\Lua::class)) {
            $manager = new ScriptEngineManager();
            $engine = $manager->getEngineByName("lua");
            $engine->put('a', 5);
            $this->assertEquals(
                120,
                $engine->eval(<<<CODE
                    factorial = function ( n )
                        if n == 1 then return 1
                        else return n * factorial( n - 1 )
                        end
                    end
                    return factorial(a)
                    CODE
                )
            );
        } else {
            $this->assertTrue(true);
        }
    }
}
