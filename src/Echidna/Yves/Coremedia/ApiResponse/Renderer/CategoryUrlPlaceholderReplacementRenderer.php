<?php



namespace Echidna\Yves\Coremedia\ApiResponse\Renderer;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToCategoryStorageClientInterface;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToStoreClientInterface;

class CategoryUrlPlaceholderReplacementRenderer implements PlaceholderReplacementRendererInterface
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'category';
    protected const PLACEHOLDER_RENDER_TYPE = 'url';

    /**
     * @var \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToCategoryStorageClientInterface
     */
    protected $categoryStorageClient;

    /**
     * @var \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToCategoryStorageClientInterface $categoryStorageClient
     * @param \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToStoreClientInterface $storeClient
     */
    public function __construct(
        CoremediaToCategoryStorageClientInterface $categoryStorageClient,
        CoremediaToStoreClientInterface $storeClient
    ) {
        $this->categoryStorageClient = $categoryStorageClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return bool
     */
    public function isApplicable(CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer): bool
    {
        return $coreMediaPlaceholderTransfer->getObjectType() === static::PLACEHOLDER_OBJECT_TYPE &&
            $coreMediaPlaceholderTransfer->getRenderType() === static::PLACEHOLDER_RENDER_TYPE;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    public function getPlaceholderReplacement(
        CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        if (!$coreMediaPlaceholderTransfer->getCategoryId()) {
            return null;
        }

        $categoryNodeStorageTransfer = $this->categoryStorageClient->getCategoryNodeById(
            (int)$coreMediaPlaceholderTransfer->getCategoryId(),
            $locale,
            $this->storeClient->getCurrentStore()->getName()
        );

        return $categoryNodeStorageTransfer->getUrl();
    }

    /**
     * @return string|null
     */
    public function getFallbackPlaceholderReplacement(): ?string
    {
        return '';
    }
}
