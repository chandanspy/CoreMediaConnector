<?php



namespace Echidna\Yves\Coremedia\ApiResponse\Replacer;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;

class PlaceholderReplacer implements PlaceholderReplacerInterface
{
    /**
     * @param string $content
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return string
     */
    public function replace(string $content, CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer): string
    {
        if ($coreMediaPlaceholderTransfer->getPlaceholderReplacement() === null) {
            return $content;
        }

        return str_replace(
            $coreMediaPlaceholderTransfer->getPlaceholderBody(),
            $coreMediaPlaceholderTransfer->getPlaceholderReplacement(),
            $content
        );
    }
}
