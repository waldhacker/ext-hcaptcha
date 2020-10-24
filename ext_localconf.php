<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
(static function (string $extensionKey): void {
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'hcaptcha',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        [
            'source' => 'EXT:hcaptcha/Resources/Public/Icons/hcaptcha.svg'
        ]
    );
})('hcaptcha');
