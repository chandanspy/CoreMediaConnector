<?php



namespace Echidna\Shared\Coremedia;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CoremediaConfig extends AbstractBundleConfig
{
    public const PREG_MATCH_PLACEHOLDER_KEY = 'placeholder';

    /**
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->get(CoremediaConstants::ENABLE_DEBUG, false);
    }
}
