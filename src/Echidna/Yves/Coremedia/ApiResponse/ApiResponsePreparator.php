<?php



namespace Echidna\Yves\Coremedia\ApiResponse;

use Generated\Shared\Transfer\CoremediaApiResponseTransfer;

class ApiResponsePreparator implements ApiResponsePreparatorInterface
{
    /**
     * @var \Echidna\Yves\Coremedia\ApiResponse\Resolver\ApiResponseResolverInterface[]
     */
    protected $apiResponseResolvers;

    /**
     * @param \Echidna\Yves\Coremedia\ApiResponse\Resolver\ApiResponseResolverInterface[] $apiResponseResolvers
     */
    public function __construct(array $apiResponseResolvers)
    {
        $this->apiResponseResolvers = $apiResponseResolvers;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaApiResponseTransfer $coreMediaApiResponseTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function prepare(
        CoremediaApiResponseTransfer $coreMediaApiResponseTransfer,
        string $locale
    ): CoremediaApiResponseTransfer {
        foreach ($this->apiResponseResolvers as $apiResponseResolver) {
            $coreMediaApiResponseTransfer = $apiResponseResolver->resolve($coreMediaApiResponseTransfer, $locale);
        }

        return $coreMediaApiResponseTransfer;
    }
}
