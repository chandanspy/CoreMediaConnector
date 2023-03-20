<?php



namespace EchidnaTest\Yves\Coremedia\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CategoryNodeStorageBuilder;
use Generated\Shared\DataBuilder\CoremediaApiResponseBuilder;
use Generated\Shared\DataBuilder\CoremediaFragmentRequestBuilder;
use Generated\Shared\DataBuilder\CurrencyBuilder;
use Generated\Shared\DataBuilder\CurrentProductPriceBuilder;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\CoremediaApiResponseTransfer;
use Generated\Shared\Transfer\CoremediaFragmentRequestTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

class CoremediaHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CoremediaFragmentRequestTransfer
     */
    public function getCoremediaFragmentRequestTransfer(array $seedData = []): CoremediaFragmentRequestTransfer
    {
        return (new CoremediaFragmentRequestBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function getCoremediaApiResponseTransfer(array $seedData = []): CoremediaApiResponseTransfer
    {
        return (new CoremediaApiResponseBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeStorageTransfer(array $seedData = []): CategoryNodeStorageTransfer
    {
        return (new CategoryNodeStorageBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function getPriceProductTransfer(array $seedData = []): PriceProductTransfer
    {
        return (new PriceProductBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    public function getMoneyValueTransfer(array $seedData = []): MoneyValueTransfer
    {
        return (new MoneyValueBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function getCurrentProductPriceTransfer(array $seedData = []): CurrentProductPriceTransfer
    {
        return (new CurrentProductPriceBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrencyTransfer(array $seedData = []): CurrencyTransfer
    {
        return (new CurrencyBuilder($seedData))->build();
    }
}
