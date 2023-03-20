<?php



namespace Echidna\Yves\Coremedia\ApiResponse\Executor;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;

interface IncorrectPlaceholderDataExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return void
     */
    public function executeIncorrectPlaceholderData(
        CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): void;
}
