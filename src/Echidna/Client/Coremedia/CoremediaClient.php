<?php



namespace Echidna\Client\Coremedia;

use Generated\Shared\Transfer\CoremediaApiResponseTransfer;
use Generated\Shared\Transfer\CoremediaFragmentRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Echidna\Client\Coremedia\CoremediaFactory getFactory()
 */
class CoremediaClient extends AbstractClient implements CoremediaClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function getDocumentFragment(
        CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): CoremediaApiResponseTransfer {
        return $this->getFactory()
            ->createApiClient()
            ->getDocumentFragment($coreMediaFragmentRequestTransfer);
    }
}
