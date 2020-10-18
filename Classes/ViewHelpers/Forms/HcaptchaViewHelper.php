<?php
declare(strict_types=1);

namespace Susanne\Hcaptcha\ViewHelpers\Forms;

use Susanne\Hcaptcha\Service\ConfigurationService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class HcaptchaViewHelper extends AbstractFormFieldViewHelper
{
    use CompileWithRenderStatic;

    private $configurationService;

    public function __construct(ConfigurationService $configurationService)
    {
        parent::__construct();
        $this->configurationService = $configurationService;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        GeneralUtility::makeInstance(PageRenderer::class)->addJsFooterLibrary(
            'hcaptchaapi',
            $this->configurationService->getApiScript(),
            'text/javascript',
            false,
            false,
            '',
            true,
            '|',
            true,
            '',
            true
        );

        return '<div class="h-captcha" data-sitekey="' . $this->configurationService->getPublicKey() . '"></div>';
    }
}
