<?php

namespace MIBU\Newsletter\Rules;

class Honeypot
{
    public static function defaults(): array
    {
        return [
            'present',
            'prohibited',
        ];
    }
}
