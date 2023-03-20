<?php



namespace Echidna\Yves\Coremedia\ApiResponse\Replacer\Metadata;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;

interface MetadataReplacerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return string
     */
    public function replaceMetaTag(CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer): string;
}
