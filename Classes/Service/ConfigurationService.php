<?php

declare(strict_types=1);

namespace Susanne\Hcaptcha\Service;

use Susanne\Hcaptcha\Exception\MissingKeyException;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

class ConfigurationService
{
    /**
     * @var array|null
     */
    private $settings = null;

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

        return $apiScript;
    }
}
