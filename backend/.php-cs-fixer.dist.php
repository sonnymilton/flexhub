<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'final_class' => true,
        'phpdoc_array_type' => true,
        'declare_strict_types' => true,
        'strict_param' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;
