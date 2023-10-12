<?php

namespace MIBU\Newsletter\Rules;

class Email
{
    public static function defaults(): array
    {
        return [
            'email:rfc,dns'
        ];
    }
}
