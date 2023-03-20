<?php



namespace Echidna\Yves\Coremedia\Dependency\Client;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;

interface CoremediaToCategoryStorageClientInterface
{
    /**
     * @param int $idCategoryNode
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById(int $idCategoryNode, string $localeName, string $storeName): CategoryNodeStorageTransfer;
}
