<?php

$EM_CONF[$_EXTKEY] = [
    'title'            => 'hCaptcha for EXT:form',
    'description'      => 'TYPO3 Extension to add hCaptcha to EXT:form - The privacy friendly captcha alternative.',
    'category'         => 'frontend',
    'author'           => 'waldhacker',
    'author_email'     => 'hello@waldhacker.dev',
    'author_company'   => 'waldhacker UG (haftungsbeschränkt)',
    'state'            => 'stable',
    'uploadfolder'     => '0',
    'clearCacheOnLoad' => 1,
    'version'          => '2.1.1',
    'constraints'      => [
        'depends' => [
            'extbase' => '10.4.0-12.4.99',
            'fluid' => '10.4.0-12.4.99',
            'form' => '10.4.0-12.4.99',
            'typo3' => '10.4.0-12.4.99',
        ],
    ],
];
