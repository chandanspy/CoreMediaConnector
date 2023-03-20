<?php



namespace Echidna\Yves\Coremedia\ApiResponse\Resolver;

use Generated\Shared\Transfer\CoremediaApiResponseTransfer;

interface ApiResponseResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoremediaApiResponseTransfer $coreMediaApiResponseTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function resolve(
        CoremediaApiResponseTransfer $coreMediaApiResponseTransfer,
        string $locale
    ): CoremediaApiResponseTransfer;
}
