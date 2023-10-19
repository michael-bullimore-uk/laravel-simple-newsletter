<?php

// https://cs.symfony.com/doc/config.html
$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__,
    ])
;

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@PSR12' => true,
        'no_unused_imports' => true,
        'trailing_comma_in_multiline' => true,
    ])
    ->setFinder($finder)
;
