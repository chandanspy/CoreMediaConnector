<?php



namespace Echidna\Yves\Coremedia\ApiResponse\PostProcessor;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;

interface PlaceholderPostProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoremediaPlaceholderTransfer
     */
    public function addReplacement(
        CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): CoremediaPlaceholderTransfer;
}
