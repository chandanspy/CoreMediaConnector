<?php



namespace Echidna\Yves\Coremedia\ApiResponse\Replacer\Metadata;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;
use Echidna\Yves\Coremedia\CoremediaConfig;

class TitleMetadataReplacer implements MetadataReplacerInterface
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
        if ($coreMediaPlaceholderTransfer->getTitle() === null) {
            return '';
        }

        return sprintf(
            $this->config->getMetaTagFormat(),
            CoremediaPlaceholderTransfer::TITLE,
            htmlentities($coreMediaPlaceholderTransfer->getTitle())
        );
    }
}
