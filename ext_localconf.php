<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}
(static function (string $extensionKey): void {

    // TypoScript Constants
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
        '<INCLUDE_TYPOSCRIPT:source="FILE:EXT:' . $extensionKey . '/Configuration/TypoScript/constants.typoscript">'
    );
    // TypoScript Setup
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
        '<INCLUDE_TYPOSCRIPT:source="FILE:EXT:' . $extensionKey . '/Configuration/TypoScript/setup.typoscript">'
    );

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'hcaptcha',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        [
            'source' => 'EXT:hcaptcha/Resources/Public/Icons/hcaptcha.svg'
        ]
    );
})('hcaptcha');
