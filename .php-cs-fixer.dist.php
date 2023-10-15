<?php

declare(strict_types=1);

$header = <<<'COMMENT'
This file is part of the PIDIA.
(c) Carlos Chininin <cio@pidia.pe>
COMMENT;

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHPUnit57Migration:risky' => true,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'native_constant_invocation' => [
            'fix_built_in' => false,
        ],
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'escape_implicit_backslashes' => true,
        'explicit_indirect_variable' => true,
        'final_internal_class' => true,
        'fopen_flags' => [
            'b_mode' => true,
        ],
        'header_comment' => [
            'header' => $header,
            'separate' => 'both',
        ],
        'heredoc_to_nowdoc' => true,
        'linebreak_after_opening_tag' => true,
        'list_syntax' => [
            'syntax' => 'short',
        ],
        'mb_str_functions' => true,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'no_php4_constructor' => true,
        'echo_tag_syntax' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'phpunit',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],
        'ordered_imports' => true,
        'php_unit_strict' => true,
        'php_unit_no_expectation_annotation' => true,
        'php_unit_test_class_requires_covers' => true,
        'phpdoc_order' => true,
        'phpdoc_summary' => false,
        'psr_autoloading' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'declare_strict_types' => true,
    ])
    ->setCacheFile(__DIR__.'/var/.php_cs.cache');
