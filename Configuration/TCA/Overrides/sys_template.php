<?php

defined('TYPO3_MODE') || die();

(static function (string $extensionKey): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $extensionKey,
        'Configuration/TypoScript',
        'hCaptcha Configuration'
    );
}
)('hcaptcha');
