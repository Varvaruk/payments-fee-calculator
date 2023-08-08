<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$config = new Config();
$config
    ->setRules([
        'array_syntax' => ['syntax' => 'short'],
        'indentation_type' => true,
        'line_ending' => true,
        // Додайте тут інші правила, які вам потрібні
    ])
    ->setFinder(
        Finder::create()
            ->in(__DIR__)
    );

return $config;
