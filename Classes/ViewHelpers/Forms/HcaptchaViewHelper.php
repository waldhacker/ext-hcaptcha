<?php

declare(strict_types=1);

namespace Susanne\Hcaptcha\ViewHelpers\Forms;

use Susanne\Hcaptcha\Service\ConfigurationService;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * @codeCoverageIgnore maybe test with an acceptance test at a later point
 */
class HcaptchaViewHelper extends AbstractFormFieldViewHelper
{
    use CompileWithRenderStatic;

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
        parent::__construct();
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
