<?php



namespace Echidna\Yves\Coremedia;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use Echidna\Shared\Coremedia\CoremediaConfig as SharedCoremediaConfig;

/**
 * @method \Echidna\Shared\Coremedia\CoremediaConfig getSharedConfig()
 */
class CoremediaConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->getSharedConfig()->isDebugModeEnabled();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPlaceholderPattern(): string
    {
        return '/(?:(?:&lt;|<)!--CM\s*)(?P<' . SharedCoremediaConfig::PREG_MATCH_PLACEHOLDER_KEY . '>(?:(?!CM--(&gt;|>)).|\s)*)(?:\s*\CM--(?:&gt;|>))/i';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getMetaTagFormat(): string
    {
        return '<meta name="%s" content="%s">';
    }
}
