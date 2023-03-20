<?php



namespace Echidna\Client\Coremedia\Api\Builder;

use Generated\Shared\Transfer\CoremediaFragmentRequestTransfer;

interface UrlBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return string
     */
    public function buildDocumentFragmentApiUrl(
        CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): string;
}
