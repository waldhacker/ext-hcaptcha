<?php

declare(strict_types=1);

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
        $configurationService = new ConfigurationService($this->configurationManager->reveal());
        $configurationService->getPublicKey();
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

        $configurationService = new ConfigurationService($this->configurationManager->reveal());
        $publicKey = $configurationService->getPublicKey();

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

        $configurationService = new ConfigurationService($this->configurationManager->reveal());
        $publicKey = $configurationService->getPublicKey();

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
        $configurationService = new ConfigurationService($this->configurationManager->reveal());
        $configurationService->getPrivateKey();
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

        $configurationService = new ConfigurationService($this->configurationManager->reveal());
        $privateKey = $configurationService->getPrivateKey();

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

        $configurationService = new ConfigurationService($this->configurationManager->reveal());
        $privateKey = $configurationService->getPrivateKey();

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
        $configurationService = new ConfigurationService($this->configurationManager->reveal());
        $configurationService->getVerificationServer();
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

        $configurationService = new ConfigurationService($this->configurationManager->reveal());
        $verificationServer = $configurationService->getVerificationServer();

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

        $configurationService = new ConfigurationService($this->configurationManager->reveal());
        $verificationServer = $configurationService->getVerificationServer();

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
        $configurationService = new ConfigurationService($this->configurationManager->reveal());
        $configurationService->getApiScript();
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

        $configurationService = new ConfigurationService($this->configurationManager->reveal());
        $apiScript = $configurationService->getApiScript();

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

        $configurationService = new ConfigurationService($this->configurationManager->reveal());
        $apiScript = $configurationService->getApiScript();

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
