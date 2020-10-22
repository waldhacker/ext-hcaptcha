<?php

declare(strict_types=1);

namespace Susanne\Hcaptcha\Validation;

use Susanne\Hcaptcha\Service\ConfigurationService;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class HcaptchaValidator extends AbstractValidator
{
    /**
     * @var ConfigurationService
     */
    private $configurationService;

    public function injectConfigurationService(ConfigurationService $configurationService): void
    {
        $this->configurationService = $configurationService;
    }

    /**
     * Validate the captcha value from the request and add an error if not valid
     *
     * @param mixed $value The value
     */
    public function isValid($value): void
    {
        $response = $this->validateHcaptcha();

        if (empty($response) || $response['success'] === false) {
            foreach ($response['error-codes'] as $errorCode) {
                $this->addError(
                    $this->translateErrorMessage(
                        'error_hcaptcha_' . $errorCode,
                        'hcaptcha'
                    ) ?? '',
                    1566209403
                );
            }
        }
    }

    /**
     * @return array
     */
    public function validateHcaptcha(): array
    {
        /** @var ServerRequest $request */
        $request = $GLOBALS['TYPO3_REQUEST'];
        /** @var array $parsedBody */
        $parsedBody = $request->getParsedBody();
        $hcaptchaFormFieldValue = $parsedBody['h-captcha-response'] ?? null;

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

        $requestService = GeneralUtility::makeInstance(RequestFactory::class);
        $response = $requestService->request($url, 'POST');

        $body = (string)$response->getBody();
        return \GuzzleHttp\json_decode($body, true);
    }
}
