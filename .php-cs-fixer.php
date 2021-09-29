<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__.'/config')
    ->in(__DIR__.'/database')
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests')
;

return (new Config)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'single_quote' => true,
        'visibility_required' => false,
    ])
    ->setFinder($finder);
