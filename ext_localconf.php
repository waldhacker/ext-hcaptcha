<?php

defined('TYPO3') or die();

call_user_func(static function () {
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'hcaptcha',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        [
            'source' => 'EXT:hcaptcha/Resources/Public/Icons/hcaptcha.svg',
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
        'hcaptcha',
        'setup',
        'module.tx_form {
          settings {
            yamlConfigurations {
              158329071148 = EXT:hcaptcha/Configuration/Form/Yaml/BaseSetup.yaml
            }
          }
        }'
    );
});
