<?php

namespace Script\Engine\Juel;

use El\ExpressionFactory;
use Juel\ExpressionFactoryImpl;

abstract class ExpressionFactoryResolver
{
    public static function resolveExpressionFactory(): ExpressionFactory
    {
        // Return instance of custom JUEL implementation
        return new ExpressionFactoryImpl();
    }
}
