<?php

declare(strict_types=1);

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive(
    $GLOBALS['TYPO3_CONF_VARS'],
    [
        'LOG' => [
            'writerConfiguration' => [
                \TYPO3\CMS\Core\Log\LogLevel::ERROR => [
                    \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [],
                ],
                \TYPO3\CMS\Core\Log\LogLevel::WARNING => [
                    \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [],
                ],
                \TYPO3\CMS\Core\Log\LogLevel::NOTICE => [
                    \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [],
                ],
                \TYPO3\CMS\Core\Log\LogLevel::INFO => [
                    \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [],
                ],
                \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
                    \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [],
                ],
            ],
        ],
        'MAIL' => [
            'defaultMailFromAddress' => 'hello@waldhacker.dev',
            'defaultMailFromName' => 'waldhacker',
            'transport' => 'mbox',
            'transport_spool_type' => 'file',
            'transport_spool_filepath' => \Waldhacker\Hcaptcha\Tests\Functional\FunctionalTestCase::MAIL_SPOOL_FOLDER,
        ],
        'SC_OPTIONS' => [
            'Core/TypoScript/TemplateService' => [
                'runThroughTemplatesPostProcessing' => [
                    // Register hooks for frontend test
                    //'FunctionalTest' => \TYPO3\TestingFramework\Core\Functional\Framework\Frontend\Hook\TypoScriptInstructionModifier::class . '->apply',
                ],
            ],
        ],
    ]
);
