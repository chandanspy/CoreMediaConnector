<?php



namespace Echidna\Yves\Coremedia\Dependency\Client;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;

class CoremediaToPriceProductClientBridge implements CoremediaToPriceProductClientInterface
{
    /**
     * @var \Spryker\Client\PriceProduct\PriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @param \Spryker\Client\PriceProduct\PriceProductClientInterface $priceProductClient
     */
    public function __construct($priceProductClient)
    {
        $this->priceProductClient = $priceProductClient;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductPriceTransfer(array $priceProductTransfers): CurrentProductPriceTransfer
    {
        return $this->priceProductClient->resolveProductPriceTransfer($priceProductTransfers);
    }
}
