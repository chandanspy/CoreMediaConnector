<?php



namespace Echidna\Yves\Coremedia\Reader\CmsSlotContent;

use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use Generated\Shared\Transfer\CmsSlotContentResponseTransfer;
use Echidna\Client\Coremedia\CoremediaClientInterface;
use Echidna\Yves\Coremedia\ApiResponse\ApiResponsePreparatorInterface;
use Echidna\Yves\Coremedia\Mapper\ApiContextMapperInterface;

class CmsSlotContentReader implements CmsSlotContentReaderInterface
{
    /**
     * @var \Echidna\Client\Coremedia\CoremediaClientInterface
     */
    protected $coreMediaClient;

    /**
     * @var \Echidna\Yves\Coremedia\Mapper\ApiContextMapperInterface
     */
    protected $apiContextMapper;

    /**
     * @var \Echidna\Yves\Coremedia\ApiResponse\ApiResponsePreparatorInterface
     */
    protected $apiResponsePreparator;

    /**
     * @param \Echidna\Client\Coremedia\CoremediaClientInterface $coreMediaClient
     * @param \Echidna\Yves\Coremedia\Mapper\ApiContextMapperInterface $apiContextMapper
     * @param \Echidna\Yves\Coremedia\ApiResponse\ApiResponsePreparatorInterface $apiResponsePreparator
     */
    public function __construct(
        CoremediaClientInterface $coreMediaClient,
        ApiContextMapperInterface $apiContextMapper,
        ApiResponsePreparatorInterface $apiResponsePreparator
    ) {
        $this->coreMediaClient = $coreMediaClient;
        $this->apiContextMapper = $apiContextMapper;
        $this->apiResponsePreparator = $apiResponsePreparator;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentResponseTransfer
     */
    public function getDocumentFragment(
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): CmsSlotContentResponseTransfer {
        $coreMediaFragmentRequestTransfer = $this->apiContextMapper->mapCmsSlotContentRequestToCoremediaFragmentRequest(
            $cmsSlotContentRequestTransfer
        );
        $coreMediaApiResponseTransfer = $this->coreMediaClient->getDocumentFragment($coreMediaFragmentRequestTransfer);

        if (!$coreMediaApiResponseTransfer->getIsSuccessful()) {
            return (new CmsSlotContentResponseTransfer())->setContent('');
        }

        $coreMediaApiResponseTransfer = $this->apiResponsePreparator->prepare(
            $coreMediaApiResponseTransfer,
            $coreMediaFragmentRequestTransfer->getLocale()
        );

        return $this->apiContextMapper
            ->mapCoremediaApiResponseTransferToCmsSlotContentResponseTransfer($coreMediaApiResponseTransfer);
    }
}
