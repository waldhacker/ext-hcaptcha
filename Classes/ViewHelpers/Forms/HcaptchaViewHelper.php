<?php

declare(strict_types=1);

namespace Waldhacker\Hcaptcha\ViewHelpers\Forms;

use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use Waldhacker\Hcaptcha\Service\ConfigurationService;

/**
 * @codeCoverageIgnore maybe test with an acceptance test at a later point
 */
class HcaptchaViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    protected $escapeOutput = false;

    /**
     * @var ConfigurationService
     */
    private $configurationService;

    /**
     * @var AssetCollector
     */
    private $assetCollector;

    public function __construct(ConfigurationService $configurationService, AssetCollector $assetCollector)
    {
        $this->configurationService = $configurationService;
        $this->assetCollector = $assetCollector;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $this->assetCollector->addJavaScript(
            'hcaptcha',
            $this->configurationService->getApiScript(),
            ['async' => '', 'defer' => '']
        );
        return '<div class="h-captcha" data-sitekey="' . $this->configurationService->getPublicKey() . '"></div>';
    }
}
