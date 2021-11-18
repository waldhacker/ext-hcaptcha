<?php

declare(strict_types=1);

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive(
    $GLOBALS['TYPO3_CONF_VARS'],
    [
        'LOG' => [
        ],
        'MAIL' => [
            'defaultMailFromAddress' => 'hello@waldhacker.dev',
            'defaultMailFromName' => 'waldhacker',
            'transport' => 'mbox',
            'transport_spool_type' => 'file',
            'transport_spool_filepath' => \Waldhacker\Hcaptcha\Tests\Functional\FunctionalTestCase::MAIL_SPOOL_FOLDER,
        ],
    ]
);
