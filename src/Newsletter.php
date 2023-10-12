<?php

namespace MIBU\Newsletter;

class Newsletter
{
    public static bool $registersRoutes = true;

    public static function ignoreRoutes(): static
    {
        static::$registersRoutes = false;

        return new static();
    }
}
