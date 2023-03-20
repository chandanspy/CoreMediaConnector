<?php



namespace Echidna\Yves\Coremedia\Dependency\Client;

interface CoremediaToStoreClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore();
}
