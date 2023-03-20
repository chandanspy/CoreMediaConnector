<?php



namespace Echidna\Yves\Coremedia\ApiResponse\Executor;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Echidna\Yves\Coremedia\CoremediaConfig;
use Echidna\Yves\Coremedia\Exception\InvalidPlaceholderDataException;

class IncorrectPlaceholderDataExecutor implements IncorrectPlaceholderDataExecutorInterface
{
    /**
     * @var \Echidna\Yves\Coremedia\CoremediaConfig
     */
    protected $config;

    /**
     * @param \Echidna\Yves\Coremedia\CoremediaConfig $coreMediaConfig
     */
    public function __construct(CoremediaConfig $coreMediaConfig)
    {
        $this->config = $coreMediaConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return void
     */
    public function executeIncorrectPlaceholderData(
        CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): void {
        if (!$this->config->isDebugModeEnabled()) {
            return;
        }

        $dataException = new InvalidPlaceholderDataException(
            sprintf(
                "Cannot obtain placeholder replacement for:\n[Placeholder]: %s\n[Locale]: %s",
                $coreMediaPlaceholderTransfer->getPlaceholderBody(),
                $locale
            )
        );

        ErrorLogger::getInstance()->log($dataException);
    }
}
