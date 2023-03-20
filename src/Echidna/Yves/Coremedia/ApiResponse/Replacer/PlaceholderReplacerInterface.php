<?php



namespace Echidna\Yves\Coremedia\ApiResponse\Replacer;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;

interface PlaceholderReplacerInterface
{
    /**
     * @param string $content
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return string
     */
    public function replace(string $content, CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer): string;
}
