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

namespace Waldhacker\Hcaptcha\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use Waldhacker\Hcaptcha\Exception\MissingKeyException;
use Waldhacker\Hcaptcha\Service\ConfigurationService;

/**
 * @coversDefaultClass \Waldhacker\Hcaptcha\Service\ConfigurationService
 */
class ConfigurationServiceTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var ConfigurationManager|ObjectProphecy
     */
    private $configurationManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->configurationManager = $this->prophesize(ConfigurationManager::class);
        $this->configurationManager->getConfiguration(Argument::cetera())->willReturn([]);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getPublicKey
     */
    public function getPublicKeyThrowsExceptionIfKeyNotSet(): void
    {
        $this->expectException(MissingKeyException::class);
        $subject = new ConfigurationService($this->configurationManager->reveal());
        $subject->getPublicKey();
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getPublicKey
     */
    public function getPublicKeyReturnsKeyFromSettings(): void
    {
        $expectedKey = 'my_superb_key';
        $this->configurationManager
            ->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'hcaptcha')
            ->willReturn(['publicKey' => $expectedKey]);

        $subject = new ConfigurationService($this->configurationManager->reveal());
        $publicKey = $subject->getPublicKey();

        self::assertSame($expectedKey, $publicKey);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getPublicKey
     */
    public function getPublicKeyReturnsKeyFromEnv(): void
    {
        $expectedKey = 'my_superb_key';
        putenv('HCAPTCHA_PUBLIC_KEY=' . $expectedKey);

        $subject = new ConfigurationService($this->configurationManager->reveal());
        $publicKey = $subject->getPublicKey();

        self::assertSame($expectedKey, $publicKey);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getPrivateKey
     */
    public function getPrivateKeyThrowsExceptionIfKeyNotSet(): void
    {
        $this->expectException(MissingKeyException::class);
        $subject = new ConfigurationService($this->configurationManager->reveal());
        $subject->getPrivateKey();
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getPrivateKey
     */
    public function getPrivateKeyReturnsKeyFromSettings(): void
    {
        $expectedKey = 'my_superb_key';
        $this->configurationManager
            ->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'hcaptcha')
            ->willReturn(['privateKey' => $expectedKey]);

        $subject = new ConfigurationService($this->configurationManager->reveal());
        $privateKey = $subject->getPrivateKey();

        self::assertSame($expectedKey, $privateKey);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getPrivateKey
     */
    public function getPrivateKeyReturnsKeyFromEnv(): void
    {
        $expectedKey = 'my_superb_key';
        putenv('HCAPTCHA_PRIVATE_KEY=' . $expectedKey);

        $subject = new ConfigurationService($this->configurationManager->reveal());
        $privateKey = $subject->getPrivateKey();

        self::assertSame($expectedKey, $privateKey);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getVerificationServer
     */
    public function getVerificationServerThrowsExceptionIfKeyNotSet(): void
    {
        $this->expectException(MissingKeyException::class);
        $subject = new ConfigurationService($this->configurationManager->reveal());
        $subject->getVerificationServer();
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getVerificationServer
     */
    public function getVerificationServerReturnsKeyFromSettings(): void
    {
        $expectedServer = 'https://example.com';
        $this->configurationManager
            ->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'hcaptcha')
            ->willReturn(['verificationServer' => $expectedServer]);

        $subject = new ConfigurationService($this->configurationManager->reveal());
        $verificationServer = $subject->getVerificationServer();

        self::assertSame($expectedServer, $verificationServer);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getVerificationServer
     */
    public function getVerificationServerReturnsKeyFromEnv(): void
    {
        $expectedServer = 'https://example.com';
        putenv('HCAPTCHA_VERIFICATION_SERVER=' . $expectedServer);

        $subject = new ConfigurationService($this->configurationManager->reveal());
        $verificationServer = $subject->getVerificationServer();

        self::assertSame($expectedServer, $verificationServer);
    }
    /**
     * @test
     * @covers ::__construct
     * @covers ::getApiScript
     */
    public function getApiScriptThrowsExceptionIfKeyNotSet(): void
    {
        $this->expectException(MissingKeyException::class);
        $subject = new ConfigurationService($this->configurationManager->reveal());
        $subject->getApiScript();
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getApiScript
     */
    public function getApiScriptReturnsKeyFromSettings(): void
    {
        $expectedScript = 'https://hcaptcha.com/1/api.js';
        $this->configurationManager
            ->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'hcaptcha')
            ->willReturn(['apiScript' => $expectedScript]);

        $subject = new ConfigurationService($this->configurationManager->reveal());
        $apiScript = $subject->getApiScript();

        self::assertSame($expectedScript, $apiScript);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getApiScript
     */
    public function getApiScriptReturnsKeyFromEnv(): void
    {
        $expectedScript = 'https://hcaptcha.com/1/api.js';
        putenv('HCAPTCHA_API_SCRIPT=' . $expectedScript);

        $subject = new ConfigurationService($this->configurationManager->reveal());
        $apiScript = $subject->getApiScript();

        self::assertSame($expectedScript, $apiScript);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        putenv('HCAPTCHA_PUBLIC_KEY');
        putenv('HCAPTCHA_PRIVATE_KEY');
        putenv('HCAPTCHA_VERIFICATION_SERVER');
        putenv('HCAPTCHA_API_SCRIPT');
    }
}
