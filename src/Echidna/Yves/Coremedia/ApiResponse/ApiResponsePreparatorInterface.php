<?php



namespace Echidna\Yves\Coremedia\ApiResponse;

use Generated\Shared\Transfer\CoremediaApiResponseTransfer;

interface ApiResponsePreparatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoremediaApiResponseTransfer $coreMediaApiResponseTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function prepare(
        CoremediaApiResponseTransfer $coreMediaApiResponseTransfer,
        string $locale
    ): CoremediaApiResponseTransfer;
}
