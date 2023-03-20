<?php



namespace Echidna\Yves\Coremedia\Formatter;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToMoneyClientInterface;

class ProductPriceFormatter implements ProductPriceFormatterInterface
{
    /**
     * @var \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToMoneyClientInterface
     */
    protected $moneyClient;

    /**
     * @param \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToMoneyClientInterface $moneyClient
     */
    public function __construct(CoremediaToMoneyClientInterface $moneyClient)
    {
        $this->moneyClient = $moneyClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     *
     * @return string
     */
    public function getFormattedProductPrice(CurrentProductPriceTransfer $currentProductPriceTransfer): string
    {
        $moneyTransfer = (new MoneyTransfer())
            ->setCurrency($currentProductPriceTransfer->getCurrency())
            ->setAmount((string)$currentProductPriceTransfer->getPrice());

        return $this->moneyClient->formatWithSymbol($moneyTransfer);
    }
}
