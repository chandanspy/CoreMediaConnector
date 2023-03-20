<?php



namespace Echidna\Yves\Coremedia\Dependency\Client;

use Generated\Shared\Transfer\MoneyTransfer;

interface CoremediaToMoneyClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithSymbol(MoneyTransfer $moneyTransfer): string;
}
