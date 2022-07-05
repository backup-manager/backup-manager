<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/spec')
    ->in(__DIR__.'/examples')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
    ])
    ->setFinder($finder)
;
