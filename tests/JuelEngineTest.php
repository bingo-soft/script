<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Script\{
    SimpleBindings,
    ScriptEngineManager,
    SimpleScriptContext
};
use Script\Engine\Juel\{
    JuelScriptEngine
};
use Juel\{
    ExpressionFactoryImpl,
    SimpleContext
};

class JuelEngineTest extends TestCase
{
    public function testEngineFactory(): void
    {
        $engine = new JuelScriptEngine();

        $factory = $engine->getFactory();

        $this->assertEquals("juel", $factory->getEngineName());
        $this->assertEquals("1.0", $factory->getEngineVersion());
        $this->assertEquals("2.1 EL", $factory->getLanguageName());
        $this->assertEquals("2.1", $factory->getLanguageVersion());

        $this->assertEquals(5, $engine->eval('${2 + 3}'));
    }

    public function testJuelEngineManagerWithVariable(): void
    {
        $manager = new ScriptEngineManager();
        $engine = $manager->getEngineByName("juel");
        $engine->put("a", 10);
        $this->assertEquals(12, $engine->eval('${a + 2}'));
    }

    public function testJuelEngineManagerWithFunction(): void
    {
        $manager = new ScriptEngineManager();
        $engine = $manager->getEngineByName("juel");

        $simple = new class () {
            public $propFloat = 1.23;

            public function foo(): int
            {
                return 11;
            }

            public function bar(): int
            {
                return 23;
            }
        };
        $engine->put("simple", $simple);

        $this->assertEquals(3.23, $engine->eval('${simple.propFloat + 2}'));
        $this->assertEquals(34, $engine->eval('${simple.bar() + simple.foo()}'));
    }
}
