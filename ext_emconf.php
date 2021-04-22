<?php

$EM_CONF[$_EXTKEY] = [
    'title'            => 'hCaptcha for EXT:form',
    'description'      => 'Privacy friendly alternative to Google\'s hcaptcha for EXT:form',
    'category'         => 'frontend',
    'author'           => 'Susanne Moog, Ralf Zimmermann',
    'author_email'     => 'look+typo3@susi.dev, hello@waldhacker.dev',
    'author_company'   => '',
    'state'            => 'stable',
    'uploadfolder'     => '0',
    'clearCacheOnLoad' => 1,
    'version'          => '1.1.0',
    'constraints'      => [
        'depends' => [
            'typo3' => '10.4.0-11.5.99',
            'form' => '10.4.0-11.5.99'
        ]
    ]
];
