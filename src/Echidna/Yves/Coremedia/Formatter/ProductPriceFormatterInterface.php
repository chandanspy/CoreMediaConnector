<?php



namespace Echidna\Yves\Coremedia\Formatter;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;

interface ProductPriceFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     *
     * @return string
     */
    public function getFormattedProductPrice(CurrentProductPriceTransfer $currentProductPriceTransfer): string;
}
