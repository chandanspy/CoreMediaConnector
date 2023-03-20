<?php



namespace Echidna\Yves\Coremedia\ApiResponse\Replacer\Metadata;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;
use Echidna\Yves\Coremedia\CoremediaConfig;

class PageNameMetadataReplacer implements MetadataReplacerInterface
{
    /**
     * @var \Echidna\Yves\Coremedia\CoremediaConfig
     */
    protected $config;

    /**
     * @param \Echidna\Yves\Coremedia\CoremediaConfig $config
     */
    public function __construct(CoremediaConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return string
     */
    public function replaceMetaTag(CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer): string
    {
        if ($coreMediaPlaceholderTransfer->getPageName() === null) {
            return '';
        }

        return sprintf(
            $this->config->getMetaTagFormat(),
            CoremediaPlaceholderTransfer::PAGE_NAME,
            htmlentities($coreMediaPlaceholderTransfer->getPageName())
        );
    }
}
