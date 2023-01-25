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

namespace Waldhacker\Hcaptcha\Tests\Functional;

use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use Waldhacker\Hcaptcha\Tests\Functional\Form\DataExtractor;
use Waldhacker\Hcaptcha\Tests\Functional\Form\DataPusher;

class HcaptchaValidationTest extends FunctionalTestCase
{
    public function validationFailsOnMultiStepFormIfHcaptchaParametersAreMissingDataProvider(): \Generator
    {
        yield 'missing hcaptcha, h-captcha-response and g-recaptcha-response parameters' => [
            'formData' => [
                'name' => 'some name',
                'subject' => 'some subject',
                'email' => 'sender@waldhacker.dev',
                'message' => 'some message',
            ],
            'formDataNoPrefix' => [
            ],
        ];

        yield 'missing h-captcha-response and g-recaptcha-response parameters' => [
            'formData' => [
                'name' => 'some name',
                'subject' => 'some subject',
                'email' => 'sender@waldhacker.dev',
                'message' => 'some message',
                'hcaptcha-1' => '1',
            ],
            'formDataNoPrefix' => [
            ],
        ];

        yield 'missing hcaptcha and h-captcha-response parameters' => [
            'formData' => [
                'name' => 'some name',
                'subject' => 'some subject',
                'email' => 'sender@waldhacker.dev',
                'message' => 'some message',
            ],
            'formDataNoPrefix' => [
                'g-recaptcha-response' => '123',
            ],
        ];

        yield 'missing h-captcha-response parameters' => [
            'formData' => [
                'name' => 'some name',
                'subject' => 'some subject',
                'email' => 'sender@waldhacker.dev',
                'message' => 'some message',
                'hcaptcha-1' => '1',
            ],
            'formDataNoPrefix' => [
                'g-recaptcha-response' => '123',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider validationFailsOnMultiStepFormIfHcaptchaParametersAreMissingDataProvider
     */
    public function validationFailsOnMultiStepFormIfHcaptchaParametersAreMissing(
        array $formData,
        array $formDataNoPrefix
    ): void {
        $uri = self::ROOT_PAGE_BASE_URI . '/multistep-test-form';

        $response = $this->executeFrontendSubRequest(new InternalRequest($uri), $this->internalRequestContext, true);
        $pageMarkup = (string)$response->getBody();

        $dataPusher = new DataPusher(new DataExtractor($pageMarkup));
        foreach ($formData as $identifier => $value) {
            $dataPusher->with($identifier, $value);
        }
        foreach ($formDataNoPrefix as $identifier => $value) {
            $dataPusher->withNoPrefix($identifier, $value);
        }
        $formPostRequest = $dataPusher->toPostRequest(new InternalRequest($uri));

        $response = $this->executeFrontendSubRequest($formPostRequest, $this->internalRequestContext, true);
        $pageMarkup = (string)$response->getBody();

        $formData = (new DataExtractor($pageMarkup))->getFormData();
        $elementData = $formData['elementData'];

        self::assertEquals(1, (int)$elementData['tx_form_formframework[multistep-test-form-1][__currentPage]']['value']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[multistep-test-form-1][name]']['class']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[multistep-test-form-1][subject]']['class']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[multistep-test-form-1][email]']['class']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[multistep-test-form-1][message]']['class']);
        self::assertStringContainsString('Missing validation value in POST request.', $pageMarkup);
        self::assertStringNotContainsString('Confirmation text', $pageMarkup);
        self::assertCount(0, $this->getMailSpoolMessages());
    }

    public function validationFailsOnMultiStepFormIfHcaptchaParametersAreInvalidDataProvider(): \Generator
    {
        yield 'h-captcha-response parameter is empty' => [
            'formData' => [
                'name' => 'some name',
                'subject' => 'some subject',
                'email' => 'sender@waldhacker.dev',
                'message' => 'some message',
            ],
            'formDataNoPrefix' => [
                'h-captcha-response' => '',
            ],
            'expectedErrorMessage' => 'Missing input.',
        ];

        yield 'h-captcha-response parameter is invalid' => [
            'formData' => [
                'name' => 'some name',
                'subject' => 'some subject',
                'email' => 'sender@waldhacker.dev',
                'message' => 'some message',
            ],
            'formDataNoPrefix' => [
                'h-captcha-response' => '123456',
            ],
            'expectedErrorMessage' => 'Invalid input response.',
        ];
    }

    /**
     * @test
     * @dataProvider validationFailsOnMultiStepFormIfHcaptchaParametersAreInvalidDataProvider
     */
    public function validationFailsOnMultiStepFormIfHcaptchaParametersAreInvalid(
        array $formData,
        array $formDataNoPrefix,
        string $expectedErrorMessage
    ): void {
        $uri = self::ROOT_PAGE_BASE_URI . '/multistep-test-form';

        $response = $this->executeFrontendSubRequest(new InternalRequest($uri), $this->internalRequestContext, true);
        $pageMarkup = (string)$response->getBody();

        $dataPusher = new DataPusher(new DataExtractor($pageMarkup));
        foreach ($formData as $identifier => $value) {
            $dataPusher->with($identifier, $value);
        }
        foreach ($formDataNoPrefix as $identifier => $value) {
            $dataPusher->withNoPrefix($identifier, $value);
        }
        $formPostRequest = $dataPusher->toPostRequest(new InternalRequest($uri));

        $response = $this->executeFrontendSubRequest($formPostRequest, $this->internalRequestContext, true);
        $pageMarkup = (string)$response->getBody();

        $formData = (new DataExtractor($pageMarkup))->getFormData();
        $elementData = $formData['elementData'];

        self::assertEquals(1, (int)$elementData['tx_form_formframework[multistep-test-form-1][__currentPage]']['value']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[multistep-test-form-1][name]']['class']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[multistep-test-form-1][subject]']['class']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[multistep-test-form-1][email]']['class']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[multistep-test-form-1][message]']['class']);
        self::assertStringNotContainsString('Confirmation text', $pageMarkup);
        self::assertStringContainsString($expectedErrorMessage, $pageMarkup);
        self::assertCount(0, $this->getMailSpoolMessages());
    }

    /**
     * @test
     */
    public function validationSuccessfulOnMultiStepFormIfHcaptchaParametersAreValid(): void
    {
        $uri = self::ROOT_PAGE_BASE_URI . '/multistep-test-form';

        $response = $this->executeFrontendSubRequest(new InternalRequest($uri), $this->internalRequestContext, true);
        $pageMarkup = (string)$response->getBody();

        $dataPusher = new DataPusher(new DataExtractor($pageMarkup));
        $formPostRequest = $dataPusher
            ->with('name', 'some name')
            ->with('subject', 'some subject')
            ->with('email', 'sender@waldhacker.dev')
            ->with('message', 'some message')
            ->withNoPrefix('h-captcha-response', self::VALID_HCAPTCHA_RESPONSE)
            ->toPostRequest(new InternalRequest($uri));

        $response = $this->executeFrontendSubRequest($formPostRequest, $this->internalRequestContext, true);
        $pageMarkup = (string)$response->getBody();

        $formData = (new DataExtractor($pageMarkup))->getFormData();
        $elementData = $formData['elementData'];

        self::assertEquals(2, (int)$elementData['tx_form_formframework[multistep-test-form-1][__currentPage]']['value']);
        self::assertStringContainsString('Summary page', $pageMarkup);
        self::assertStringNotContainsString('Confirmation text', $pageMarkup);
        self::assertCount(0, $this->getMailSpoolMessages());
    }

    public function validationFailsOnSingleStepFormIfHcaptchaParametersAreMissingDataProvider(): \Generator
    {
        yield 'missing hcaptcha, h-captcha-response and g-recaptcha-response parameters' => [
            'formData' => [
                'name' => 'some name',
                'subject' => 'some subject',
                'email' => 'sender@waldhacker.dev',
                'message' => 'some message',
            ],
            'formDataNoPrefix' => [
            ],
        ];

        yield 'missing h-captcha-response and g-recaptcha-response parameters' => [
            'formData' => [
                'name' => 'some name',
                'subject' => 'some subject',
                'email' => 'sender@waldhacker.dev',
                'message' => 'some message',
                'hcaptcha-1' => '1',
            ],
            'formDataNoPrefix' => [
            ],
        ];

        yield 'missing hcaptcha and h-captcha-response parameters' => [
            'formData' => [
                'name' => 'some name',
                'subject' => 'some subject',
                'email' => 'sender@waldhacker.dev',
                'message' => 'some message',
            ],
            'formDataNoPrefix' => [
                'g-recaptcha-response' => '123',
            ],
        ];

        yield 'missing h-captcha-response parameters' => [
            'formData' => [
                'name' => 'some name',
                'subject' => 'some subject',
                'email' => 'sender@waldhacker.dev',
                'message' => 'some message',
                'hcaptcha-1' => '1',
            ],
            'formDataNoPrefix' => [
                'g-recaptcha-response' => '123',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider validationFailsOnSingleStepFormIfHcaptchaParametersAreMissingDataProvider
     */
    public function validationFailsOnSingleStepFormIfHcaptchaParametersAreMissing(
        array $formData,
        array $formDataNoPrefix
    ): void {
        $uri = self::ROOT_PAGE_BASE_URI . '/singlestep-test-form';

        $response = $this->executeFrontendSubRequest(new InternalRequest($uri), $this->internalRequestContext, true);
        $pageMarkup = (string)$response->getBody();

        $dataPusher = new DataPusher(new DataExtractor($pageMarkup));
        foreach ($formData as $identifier => $value) {
            $dataPusher->with($identifier, $value);
        }
        foreach ($formDataNoPrefix as $identifier => $value) {
            $dataPusher->withNoPrefix($identifier, $value);
        }
        $formPostRequest = $dataPusher->toPostRequest(new InternalRequest($uri));

        $response = $this->executeFrontendSubRequest($formPostRequest, $this->internalRequestContext, true);
        $pageMarkup = (string)$response->getBody();

        $formData = (new DataExtractor($pageMarkup))->getFormData();
        $elementData = $formData['elementData'];

        self::assertEquals(1, (int)$elementData['tx_form_formframework[singlestep-test-form-2][__currentPage]']['value']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[singlestep-test-form-2][name]']['class']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[singlestep-test-form-2][subject]']['class']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[singlestep-test-form-2][email]']['class']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[singlestep-test-form-2][message]']['class']);
        self::assertStringContainsString('Missing validation value in POST request.', $pageMarkup);
        self::assertStringNotContainsString('Confirmation text', $pageMarkup);
        self::assertCount(0, $this->getMailSpoolMessages());
    }

    public function validationFailsOnSingleStepFormIfHcaptchaParametersAreInvalidDataProvider(): \Generator
    {
        yield 'h-captcha-response parameter is empty' => [
            'formData' => [
                'name' => 'some name',
                'subject' => 'some subject',
                'email' => 'sender@waldhacker.dev',
                'message' => 'some message',
            ],
            'formDataNoPrefix' => [
                'h-captcha-response' => '',
            ],
            'expectedErrorMessage' => 'Missing input.',
        ];

        yield 'h-captcha-response parameter is invalid' => [
            'formData' => [
                'name' => 'some name',
                'subject' => 'some subject',
                'email' => 'sender@waldhacker.dev',
                'message' => 'some message',
            ],
            'formDataNoPrefix' => [
                'h-captcha-response' => '123456',
            ],
            'expectedErrorMessage' => 'Invalid input response.',
        ];
    }

    /**
     * @test
     * @dataProvider validationFailsOnSingleStepFormIfHcaptchaParametersAreInvalidDataProvider
     */
    public function validationFailsOnSingleStepFormIfHcaptchaParametersAreInvalid(
        array $formData,
        array $formDataNoPrefix,
        string $expectedErrorMessage
    ): void {
        $uri = self::ROOT_PAGE_BASE_URI . '/singlestep-test-form';

        $response = $this->executeFrontendSubRequest(new InternalRequest($uri), $this->internalRequestContext, true);
        $pageMarkup = (string)$response->getBody();

        $dataPusher = new DataPusher(new DataExtractor($pageMarkup));
        foreach ($formData as $identifier => $value) {
            $dataPusher->with($identifier, $value);
        }
        foreach ($formDataNoPrefix as $identifier => $value) {
            $dataPusher->withNoPrefix($identifier, $value);
        }
        $formPostRequest = $dataPusher->toPostRequest(new InternalRequest($uri));

        $response = $this->executeFrontendSubRequest($formPostRequest, $this->internalRequestContext, true);
        $pageMarkup = (string)$response->getBody();

        $formData = (new DataExtractor($pageMarkup))->getFormData();
        $elementData = $formData['elementData'];

        self::assertEquals(1, (int)$elementData['tx_form_formframework[singlestep-test-form-2][__currentPage]']['value']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[singlestep-test-form-2][name]']['class']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[singlestep-test-form-2][subject]']['class']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[singlestep-test-form-2][email]']['class']);
        self::assertStringNotContainsString('error', $elementData['tx_form_formframework[singlestep-test-form-2][message]']['class']);
        self::assertStringNotContainsString('Confirmation text', $pageMarkup);
        self::assertStringContainsString($expectedErrorMessage, $pageMarkup);
        self::assertCount(0, $this->getMailSpoolMessages());
    }

    /**
     * @test
     */
    public function validationSuccessfulOnSingleStepFormIfHcaptchaParametersAreValid(): void
    {
        $uri = self::ROOT_PAGE_BASE_URI . '/singlestep-test-form';

        $response = $this->executeFrontendSubRequest(new InternalRequest($uri), $this->internalRequestContext, true);
        $pageMarkup = (string)$response->getBody();

        $dataPusher = new DataPusher(new DataExtractor($pageMarkup));
        $formPostRequest = $dataPusher
            ->with('name', 'some name')
            ->with('subject', 'some subject')
            ->with('email', 'sender@waldhacker.dev')
            ->with('message', 'some message')
            ->withNoPrefix('h-captcha-response', self::VALID_HCAPTCHA_RESPONSE)
            ->toPostRequest(new InternalRequest($uri));

        $response = $this->executeFrontendSubRequest($formPostRequest, $this->internalRequestContext, true);
        $pageMarkup = (string)$response->getBody();

        $formData = (new DataExtractor($pageMarkup))->getFormData();
        $elementData = $formData['elementData'];
        $mails = $this->getMailSpoolMessages();

        self::assertStringContainsString('Confirmation text', $pageMarkup);
        self::assertCount(1, $this->getMailSpoolMessages());
        self::assertStringContainsString('Confirmation of your message', $mails[0]['plaintext'] ?? '');
        self::assertStringContainsString('Your message: some subject', $mails[0]['subject'] ?? '');
    }
}
