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
    'version'          => '2.0.2',
    'constraints'      => [
        'depends' => [
            'extbase' => '10.4.0-12.0.99',
            'fluid' => '10.4.0-12.0.99',
            'form' => '10.4.0-12.0.99',
            'typo3' => '10.4.0-12.0.99',
        ]
    ]
];
