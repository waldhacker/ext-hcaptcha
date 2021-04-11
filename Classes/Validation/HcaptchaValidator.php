<?php

declare(strict_types=1);

namespace Waldhacker\Hcaptcha\Validation;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use Waldhacker\Hcaptcha\Service\ConfigurationService;

class HcaptchaValidator extends AbstractValidator
{
    /**
     * @var ConfigurationService
     */
    private $configurationService;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    public function injectConfigurationService(ConfigurationService $configurationService): void
    {
        $this->configurationService = $configurationService;
    }

    public function injectRequestFactory(RequestFactory $requestFactory): void
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * Validate the captcha value from the request and add an error if not valid
     *
     * @param mixed $value The value
     */
    protected function isValid($value): void
    {
        $response = $this->validateHcaptcha();

        if (empty($response) || $response['success'] === false) {
            foreach ($response['error-codes'] as $errorCode) {
                $this->addError(
                    $this->translateErrorMessage(
                        'error_hcaptcha_' . $errorCode,
                        'hcaptcha'
                    ),
                    1566209403
                );
            }
        }
    }

    /**
     * @return array
     */
    private function validateHcaptcha(): array
    {
        /** @var ServerRequestInterface $request */
        $request = $GLOBALS['TYPO3_REQUEST'];
        /** @var array $parsedBody */
        $parsedBody = $request->getParsedBody();
        $hcaptchaFormFieldValue = $parsedBody['h-captcha-response'] ?? null;
        if (null === $hcaptchaFormFieldValue) {
            return ['success' => false, 'error-codes' => ['invalid-post-form']];
        }

        $url = HttpUtility::buildUrl(
            [
                'host' => $this->configurationService->getVerificationServer(),
                'query' => \http_build_query(
                    [
                        'secret' => $this->configurationService->getPrivateKey(),
                        'response' => $hcaptchaFormFieldValue,
                        'remoteip' => $request->getAttribute('normalizedParams')->getRemoteAddress(),
                    ]
                ),
            ]
        );

        $response = $this->requestFactory->request($url, 'POST');

        $body = (string)$response->getBody();
        $responseArray = json_decode($body, true);
        return is_array($responseArray) ? $responseArray : [];
    }

    protected function translateErrorMessage($translateKey, $extensionName, $arguments = []): string
    {
        return LocalizationUtility::translate(
                $translateKey,
                $extensionName,
                $arguments
            ) ?? 'Validating the captcha failed.';
    }
}
