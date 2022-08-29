<?php

declare(strict_types=1);

/*
 * This file is part of the hcaptcha extension for TYPO3
 * - (c) 2021 waldhacker UG (haftungsbeschränkt)
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Waldhacker\Hcaptcha\Service;

use TYPO3\CMS\Core\Http\Request;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use Waldhacker\Hcaptcha\Exception\MissingKeyException;

class ConfigurationService
{
    /**
     * @var array|null
     */
    private $settings;

    public function __construct(ConfigurationManager $configurationManager)
    {
        if ($this->settings === null) {
            $this->settings = $configurationManager->getConfiguration(
                ConfigurationManager::CONFIGURATION_TYPE_SETTINGS,
                'hcaptcha'
            );
        }
    }

    /**
     * @return string
     * @throws MissingKeyException
     */
    public function getPublicKey(): string
    {
        $publicKey = !empty($this->settings['publicKey'])
            ? $this->settings['publicKey']
            : \getenv('HCAPTCHA_PUBLIC_KEY');

        if (empty($publicKey)) {
            throw new MissingKeyException(
                'hCaptcha public key not defined',
                1603034266
            );
        }

        return $publicKey;
    }

    /**
     * @return string
     * @throws MissingKeyException
     */
    public function getPrivateKey(): string
    {
        $privateKey = !empty($this->settings['privateKey'])
            ? $this->settings['privateKey']
            : \getenv('HCAPTCHA_PRIVATE_KEY');

        if (empty($privateKey)) {
            throw new MissingKeyException(
                'hCaptcha private key not defined',
                1603034285
            );
        }

        return $privateKey;
    }

    /**
     * @return string
     * @throws MissingKeyException
     */
    public function getVerificationServer(): string
    {
        $verificationServer = !empty($this->settings['verificationServer'])
            ? $this->settings['verificationServer']
            : \getenv('HCAPTCHA_VERIFICATION_SERVER');

        if (empty($verificationServer)) {
            throw new MissingKeyException(
                'hCaptcha verification server address key not defined',
                1603034313
            );
        }

        return $verificationServer;
    }

    /**
     * @return string
     * @throws MissingKeyException
     */
    public function getApiScript(): string
    {
        $apiScript = !empty($this->settings['apiScript'])
            ? $this->settings['apiScript']
            : \getenv('HCAPTCHA_API_SCRIPT');
        if (empty($apiScript)) {
            throw new MissingKeyException(
                'hCaptcha api script not defined',
                1603034329
            );
        }

        return $this->appendSiteLanguage($apiScript);
    }

    public function appendSiteLanguage(string $apiScript): string
    {
        if ($paramString = parse_url($apiScript, PHP_URL_QUERY)) {
            parse_str($paramString, $params);
            if (isset($params['hl'])) {
                return $apiScript;
            }
        }

        $request = $this->getRequest();
        /** @var SiteLanguage $siteLanguage */
        $siteLanguage = $request->getAttribute('language');
        $params['hl'] = $siteLanguage->getTwoLetterIsoCode();
        $apiScript .= '?' . http_build_query($params);

        return $apiScript;
    }

    public function getRequest(): Request
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
