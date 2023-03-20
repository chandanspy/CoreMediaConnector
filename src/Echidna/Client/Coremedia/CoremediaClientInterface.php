<?php



namespace Echidna\Client\Coremedia;

use Generated\Shared\Transfer\CoremediaApiResponseTransfer;
use Generated\Shared\Transfer\CoremediaFragmentRequestTransfer;

interface CoremediaClientInterface
{
    /**
     * Specification:
     * - Makes a request from the CoremediaFragmentRequestTransfer.
     * - Sends the request to Coremedia REST API server to receive a document fragment.
     * - Returns a string representation of the current requested fragment.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function getDocumentFragment(
        CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): CoremediaApiResponseTransfer;
}
