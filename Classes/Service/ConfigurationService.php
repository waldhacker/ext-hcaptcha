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

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ServerRequestFactory;
use TYPO3\CMS\Core\Http\Uri;
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

    private function appendSiteLanguage(string $apiScript): string
    {
        // @codeCoverageIgnoreStart
        try {
            $uri = new Uri($apiScript);
        } catch (\Exception $e) {
            return $apiScript;
        }
        // @codeCoverageIgnoreEnd

        parse_str($uri->getQuery(), $apiScriptQueryParts);

        if (isset($apiScriptQueryParts['hl'])) {
            return $apiScript;
        }

        $request = $this->getServerRequest();
        $siteLanguage = $request->getAttribute('language');

        // @codeCoverageIgnoreStart
        if (!$siteLanguage instanceof SiteLanguage) {
            return $apiScript;
        }
        // @codeCoverageIgnoreEnd

        if (method_exists($siteLanguage, 'getTwoLetterIsoCode')) {
            $apiScriptQueryParts['hl'] = $siteLanguage->getTwoLetterIsoCode();
        } else {
            $apiScriptQueryParts['hl'] = $siteLanguage->getLocale()->getLanguageCode();
        }

        $uri = $uri->withQuery(http_build_query($apiScriptQueryParts));

        return (string)$uri;
    }

    private function getServerRequest(): ServerRequestInterface
    {
        $request = $GLOBALS['TYPO3_REQUEST'] ?? ServerRequestFactory::fromGlobals();

        // @codeCoverageIgnoreStart
        if (!($request instanceof ServerRequestInterface)) {
            throw new \InvalidArgumentException(sprintf('Request must implement "%s"', ServerRequestInterface::class), 1674637738);
        }
        // @codeCoverageIgnoreEnd

        return $request;
    }
}
