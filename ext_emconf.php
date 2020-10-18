<?php

$EM_CONF[$_EXTKEY] = [
    'title'            => 'hCaptcha for EXT:form',
    'description'      => 'Privacy friendly alternative to Google\'s hcaptcha for EXT:form',
    'category'         => 'frontend',
    'author'           => 'Susanne Moog',
    'author_email'     => 'look@susi.dev',
    'author_company'   => '',
    'state'            => 'stable',
    'uploadfolder'     => '0',
    'clearCacheOnLoad' => 1,
    'version'          => '1.0.0',
    'constraints'      => [
        'depends' => [
            'typo3' => '10.4.0-11.0.99',
            'form' => '10.4.0-11.0.99'
        ]
    ]
];
