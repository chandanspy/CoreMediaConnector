<?php



namespace EchidnaTest\Yves\Coremedia\ApiResponse;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\CoremediaApiResponseTransfer;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Echidna\Client\Coremedia\CoremediaClientInterface;
use Echidna\Yves\Coremedia\CoremediaConfig;
use Echidna\Yves\Coremedia\CoremediaFactory;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToCategoryStorageClientInterface;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToMoneyClientInterface;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductClientInterface;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductStorageClientInterface;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToProductStorageClientInterface;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToStoreClientInterface;
use Echidna\Yves\Coremedia\Dependency\Service\CoremediaToUtilEncodingServiceInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiResponsePreparatorTest extends Unit
{
    protected const IS_DEBUG_MODE_ENABLED = false;
    protected const PRODUCT_ABSTRACT_STORAGE_DATA = [
        'url' => '/en/test-product-abstract-012',
        'id_product_abstract' => 1,
    ];
    protected const PRODUCT_CONCRETE_STORAGE_DATA = [
        'url' => '/en/test-product-concrete-055',
        'id_product_abstract' => 1,
        'id_product_concrete' => 2,
    ];
    protected const CATEGORY_URL = '/en/category-12345';
    protected const PRODUCT_ABSTRACT_PRICE = 1000;
    protected const PRODUCT_CONCRETE_PRICE = 500;
    protected const CURRENCY_CODE = 'USD';
    protected const EXISTENT_ROUTE = 'cart';

    protected const API_RESPONSE_NONEXISTENT_PLACEHOLDER_OBJECT_TYPE = '<a href="&lt;!--CM {&quot;productId&quot;:&quot;012&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;nonexistent-object-type&quot;} CM--&gt;">Incorrect data</a>';

    protected const API_RESPONSE_CORRECT_DATA = '<a href="&lt;!--CM {&quot;productId&quot;:&quot;012&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;">Test product abstract</a> '
    . '<a href="&lt;!--CM {&quot;productId&quot;:&quot;055_65789012&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;">Test product concrete</a> '
    . '<a href="&lt;!--CM {&quot;categoryId&quot;:&quot;12345&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;category&quot;} CM--&gt;">Test category</a>'
    . '&lt;!--CM {&quot;renderType&quot;:&quot;metadata&quot;,&quot;objectType&quot;:&quot;page&quot;,&quot;title&quot;:&quot;testMetaTitle&quot;,&quot;description&quot;:&quot;testMetaDescription&quot;,&quot;keywords&quot;:&quot;testMetaKeywords&quot;,&quot;pageName&quot;:&quot;testMetaPageName&quot;} CM--&gt;'
    . 'Product abstract price: &lt;!--CM {&quot;productId&quot;:&quot;013&quot;,&quot;renderType&quot;:&quot;price&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;'
    . 'Product concrete price: &lt;!--CM {&quot;productId&quot;:&quot;013_34234&quot;,&quot;renderType&quot;:&quot;price&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;'
    . '<a href="&lt;!--CM {&quot;externalSeoSegment&quot;:&quot;cart&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;page&quot;} CM--&gt;">Page url</a>';

    protected const API_RESPONSE_INCORRECT_DATA = '<a href="&lt;!--CM {&quot;productId&quot;:&quot;073&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;">Test product abstract</a> '
    . '<a href="&lt;!--CM {&quot;productId&quot;:&quot;056_1234567&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;">Test product concrete</a> '
    . '<a href="&lt;!--CM {&quot;categoryId&quot;:&quot;56789&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;category&quot;} CM--&gt;">Test category</a>'
    . '<!--CM {"renderType":"metadata","objectType":"page","pbe":"pbe","slider":"slider"} CM-->'
    . 'Product abstract price: &lt;!--CM {&quot;productId&quot;:&quot;014&quot;,&quot;renderType&quot;:&quot;price&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;'
    . 'Product concrete price: &lt;!--CM {&quot;productId&quot;:&quot;014_34234&quot;,&quot;renderType&quot;:&quot;price&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;'
    . '<a href="&lt;!--CM {&quot;externalSeoSegment&quot;:&quot;nonExistentRoute&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;page&quot;} CM--&gt;">Page url</a>';

    /**
     * @var \EchidnaTest\Yves\Coremedia\CoremediaYvesTester
     */
    protected $tester;

    /**
     * @dataProvider correctApiResponseDataProvider
     *
     * @param string $correctApiResponseData
     *
     * @return void
     */
    public function testApiResponsePreparatorProvidesCorrectDataWithReplacedPlaceholders(string $correctApiResponseData): void
    {
        $unprocessedCoremediaApiResponseTransfer = $this->tester->getCoremediaApiResponseTransfer([
            CoremediaApiResponseTransfer::IS_SUCCESSFUL => true,
            CoremediaApiResponseTransfer::DATA => $correctApiResponseData,
        ]);

        $categoryNodeStorageTransfer = $this->tester->getCategoryNodeStorageTransfer([
            CategoryNodeStorageTransfer::URL => static::CATEGORY_URL,
        ]);

        $coreMediaApiResponseTransfer = $this->prepare(
            $unprocessedCoremediaApiResponseTransfer,
            static::PRODUCT_ABSTRACT_STORAGE_DATA,
            static::PRODUCT_CONCRETE_STORAGE_DATA,
            $categoryNodeStorageTransfer,
            static::EXISTENT_ROUTE
        );

        $this->assertEquals(
            $coreMediaApiResponseTransfer->getData(),
            '<a href="/en/test-product-abstract-012">Test product abstract</a> ' .
            '<a href="/en/test-product-concrete-055">Test product concrete</a> ' .
            '<a href="/en/category-12345">Test category</a>' .
            '<meta name="title" content="testMetaTitle"><meta name="description" content="testMetaDescription"><meta name="keywords" content="testMetaKeywords"><meta name="pageName" content="testMetaPageName">' .
            'Product abstract price: USD1000' .
            'Product concrete price: USD500' .
            '<a href="/cart">Page url</a>'
        );
    }

    /**
     * @dataProvider nonexistentPlaceholderObjectTypeApiResponseDataProvider
     *
     * @param string $nonexistentPlaceholderObjectTypeApiResponseData
     *
     * @return void
     */
    public function testApiResponsePreparatorReturnsTheSameDataOnIncorrectPlaceholderObjectType(
        string $nonexistentPlaceholderObjectTypeApiResponseData
    ): void {
        $unprocessedCoremediaApiResponseTransfer = $this->tester->getCoremediaApiResponseTransfer([
            CoremediaApiResponseTransfer::IS_SUCCESSFUL => true,
            CoremediaApiResponseTransfer::DATA => $nonexistentPlaceholderObjectTypeApiResponseData,
        ]);

        $categoryNodeStorageTransfer = $this->tester->getCategoryNodeStorageTransfer([
            CategoryNodeStorageTransfer::URL => static::CATEGORY_URL,
        ]);

        $coreMediaApiResponseTransfer = $this->prepare(
            $unprocessedCoremediaApiResponseTransfer,
            static::PRODUCT_ABSTRACT_STORAGE_DATA,
            static::PRODUCT_CONCRETE_STORAGE_DATA,
            $categoryNodeStorageTransfer,
            static::EXISTENT_ROUTE
        );

        $this->assertEquals(
            $coreMediaApiResponseTransfer->getData(),
            $nonexistentPlaceholderObjectTypeApiResponseData
        );
    }

    /**
     * @dataProvider incorrectApiResponseDataProvider
     *
     * @param string $incorrectApiResponseData
     *
     * @return void
     */
    public function testApiResponsePreparatorFailsOnIncorrectPlaceholdersData(string $incorrectApiResponseData): void
    {
        $unprocessedCoremediaApiResponseTransfer = $this->tester->getCoremediaApiResponseTransfer([
            CoremediaApiResponseTransfer::IS_SUCCESSFUL => true,
            CoremediaApiResponseTransfer::DATA => $incorrectApiResponseData,
        ]);

        $categoryNodeStorageTransfer = $this->tester->getCategoryNodeStorageTransfer([]);
        $categoryNodeStorageTransfer->setUrl(null);

        $coreMediaApiResponseTransfer = $this->prepare(
            $unprocessedCoremediaApiResponseTransfer,
            [],
            [],
            $categoryNodeStorageTransfer,
            'nonExistentRoute'
        );

        $this->assertEquals(
            $coreMediaApiResponseTransfer->getData(),
            '<a href="">Test product abstract</a> <a href="">Test product concrete</a> <a href="">Test category</a>'
            . '<!--CM {"renderType":"metadata","objectType":"page","pbe":"pbe","slider":"slider"} CM-->'
            . 'Product abstract price: '
            . 'Product concrete price: '
            . '<a href="">Page url</a>'
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaApiResponseTransfer $unprocessedCoremediaApiResponseTransfer
     * @param array $productAbstractStorageData
     * @param array $productConcreteStorageData
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     * @param string $externalSeoSegment
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    protected function prepare(
        CoremediaApiResponseTransfer $unprocessedCoremediaApiResponseTransfer,
        array $productAbstractStorageData,
        array $productConcreteStorageData,
        CategoryNodeStorageTransfer $categoryNodeStorageTransfer,
        string $externalSeoSegment
    ): CoremediaApiResponseTransfer {
        $productStorageClient = $this->getProductStorageClientMock(
            $productAbstractStorageData,
            $productConcreteStorageData
        );
        $categoryStorageClient = $this->getCategoryStorageClientMock($categoryNodeStorageTransfer);
        $priceProductStorageClient = $this->getPriceProductStorageClientMock();
        $priceProductClient = $this->getPriceProductClientMock();
        $moneyClient = $this->getMoneyClientMock();
        $urlGenerator = $this->getUrlGeneratorMock($externalSeoSegment);
        $storeClient = $this->getStoreClientMock();

        $apiResponsePreparator = $this->getCoremediaFactoryMock(
            $productStorageClient,
            $categoryStorageClient,
            $priceProductStorageClient,
            $priceProductClient,
            $moneyClient,
            $urlGenerator,
            $storeClient
        )->createApiResponsePreparator();

        return $apiResponsePreparator->prepare(
            $this->getCoremediaClientMock($unprocessedCoremediaApiResponseTransfer)->getDocumentFragment(
                $this->tester->getCoremediaFragmentRequestTransfer()
            ),
            'en_US'
        );
    }

    /**
     * @return \Echidna\Yves\Coremedia\Dependency\Service\CoremediaToUtilEncodingServiceInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getUtilEncodingMock(): CoremediaToUtilEncodingServiceInterface
    {
        $utilEncodingMock = $this->getMockBuilder(CoremediaToUtilEncodingServiceInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['decodeJson'])
            ->getMock();

        $utilEncodingMock
            ->method('decodeJson')
            ->willReturnCallback(function ($json, $assoc) {
                return json_decode($json, $assoc);
            });

        return $utilEncodingMock;
    }

    /**
     * @param array $productAbstractStorageData
     * @param array $productConcreteStorageData
     *
     * @return \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToProductStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getProductStorageClientMock(
        array $productAbstractStorageData,
        array $productConcreteStorageData
    ): CoremediaToProductStorageClientInterface {
        $coreMediaToProductStorageClientBridge = $this->getMockBuilder(CoremediaToProductStorageClientInterface::class)->getMock();
        $coreMediaToProductStorageClientBridge
            ->method('findProductAbstractStorageDataByMapping')
            ->willReturnCallback(function (string $mappingType, string $identifier) use ($productAbstractStorageData) {
                return strpos($identifier, '_') ? null : $productAbstractStorageData;
            });
        $coreMediaToProductStorageClientBridge
            ->method('findProductConcreteStorageDataByMapping')
            ->willReturnCallback(function (string $mappingType, string $identifier) use ($productConcreteStorageData) {
                return strpos($identifier, '_') ? $productConcreteStorageData : null;
            });

        return $coreMediaToProductStorageClientBridge;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     *
     * @return \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToCategoryStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCategoryStorageClientMock(
        CategoryNodeStorageTransfer $categoryNodeStorageTransfer
    ): CoremediaToCategoryStorageClientInterface {
        $coreMediaToCategoryStorageClientBridge = $this->getMockBuilder(CoremediaToCategoryStorageClientInterface::class)->getMock();
        $coreMediaToCategoryStorageClientBridge->method('getCategoryNodeById')->willReturn(
            $categoryNodeStorageTransfer
        );

        return $coreMediaToCategoryStorageClientBridge;
    }

    /**
     * @return \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPriceProductStorageClientMock(): CoremediaToPriceProductStorageClientInterface
    {
        $coreMediaToPriceProductStorageClientBridge = $this->getMockBuilder(CoremediaToPriceProductStorageClientInterface::class)->getMock();
        $coreMediaToPriceProductStorageClientBridge->method('getPriceProductAbstractTransfers')->willReturn([
            $this->tester->getPriceProductTransfer([
                PriceProductTransfer::MONEY_VALUE => $this->tester->getMoneyValueTransfer([
                    MoneyValueTransfer::NET_AMOUNT => static::PRODUCT_ABSTRACT_PRICE,
                ]),
            ]),
        ]);
        $coreMediaToPriceProductStorageClientBridge->method('getResolvedPriceProductConcreteTransfers')->willReturn([
            $this->tester->getPriceProductTransfer([
                PriceProductTransfer::MONEY_VALUE => $this->tester->getMoneyValueTransfer([
                    MoneyValueTransfer::NET_AMOUNT => static::PRODUCT_CONCRETE_PRICE,
                ]),
            ]),
        ]);

        return $coreMediaToPriceProductStorageClientBridge;
    }

    /**
     * @return \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPriceProductClientMock(): CoremediaToPriceProductClientInterface
    {
        $coreMediaToPriceProductClientBridge = $this->getMockBuilder(CoremediaToPriceProductClientInterface::class)->getMock();
        $coreMediaToPriceProductClientBridge->method('resolveProductPriceTransfer')->willReturnCallback(function (array $priceProductTransfers) {
            return $this->tester->getCurrentProductPriceTransfer([
                CurrentProductPriceTransfer::PRICE => $priceProductTransfers[0]->getMoneyValue()->getNetAmount(),
                CurrentProductPriceTransfer::CURRENCY => $this->tester->getCurrencyTransfer(),
            ]);
        });

        return $coreMediaToPriceProductClientBridge;
    }

    /**
     * @return \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToMoneyClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMoneyClientMock(): CoremediaToMoneyClientInterface
    {
        $coreMediaToMoneyClientBridge = $this->getMockBuilder(CoremediaToMoneyClientInterface::class)->getMock();
        $coreMediaToMoneyClientBridge->method('formatWithSymbol')->willReturnCallback(function (MoneyTransfer $moneyTransfer) {
            return static::CURRENCY_CODE . $moneyTransfer->getAmount();
        });

        return $coreMediaToMoneyClientBridge;
    }

    /**
     * @return \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToStoreClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getStoreClientMock(): CoremediaToStoreClientInterface
    {
        $storeClientMock = $this->getMockBuilder(CoremediaToStoreClientInterface::class)->getMock();

        $storeClientMock
            ->method('getCurrentStore')
            ->willReturn($this->tester->getLocator()->store()->client()->getCurrentStore());

        return $storeClientMock;
    }

    /**
     * @param \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToProductStorageClientInterface $productStorageClient
     * @param \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToCategoryStorageClientInterface $categoryStorageClient
     * @param \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductClientInterface $priceProductClient
     * @param \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToMoneyClientInterface $moneyClient
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator
     * @param \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToStoreClientInterface $storeClient
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Echidna\Yves\Coremedia\CoremediaFactory
     */
    protected function getCoremediaFactoryMock(
        CoremediaToProductStorageClientInterface $productStorageClient,
        CoremediaToCategoryStorageClientInterface $categoryStorageClient,
        CoremediaToPriceProductStorageClientInterface $priceProductStorageClient,
        CoremediaToPriceProductClientInterface $priceProductClient,
        CoremediaToMoneyClientInterface $moneyClient,
        UrlGeneratorInterface $urlGenerator,
        CoremediaToStoreClientInterface $storeClient
    ): CoremediaFactory {
        $coreMediaFactoryMock = $this->getMockBuilder(CoremediaFactory::class)
            ->setMethods([
                'getConfig',
                'getUtilEncodingService',
                'getProductStorageClient',
                'getCategoryStorageClient',
                'getPriceProductStorageClient',
                'getPriceProductClient',
                'getMoneyClient',
                'getUrlGenerator',
                'getStoreClient',
            ])->getMock();

        $coreMediaFactoryMock->method('getConfig')->willReturn(
            $this->getCoremediaConfigMock()
        );
        $coreMediaFactoryMock->method('getUtilEncodingService')->willReturn(
            $this->getUtilEncodingMock()
        );
        $coreMediaFactoryMock->method('getProductStorageClient')->willReturn($productStorageClient);
        $coreMediaFactoryMock->method('getCategoryStorageClient')->willReturn($categoryStorageClient);
        $coreMediaFactoryMock->method('getPriceProductStorageClient')->willReturn($priceProductStorageClient);
        $coreMediaFactoryMock->method('getPriceProductClient')->willReturn($priceProductClient);
        $coreMediaFactoryMock->method('getMoneyClient')->willReturn($moneyClient);
        $coreMediaFactoryMock->method('getUrlGenerator')->willReturn($urlGenerator);
        $coreMediaFactoryMock->method('getStoreClient')->willReturn($storeClient);

        return $coreMediaFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Echidna\Yves\Coremedia\CoremediaConfig
     */
    protected function getCoremediaConfigMock(): CoremediaConfig
    {
        $coreMediaConfigMock = $this->getMockBuilder(CoremediaConfig::class)
            ->setMethods(['isDebugModeEnabled'])->getMock();
        $coreMediaConfigMock->method('isDebugModeEnabled')->willReturn(static::IS_DEBUG_MODE_ENABLED);

        return $coreMediaConfigMock;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaApiResponseTransfer $coreMediaApiResponseTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Echidna\Client\Coremedia\CoremediaClientInterface
     */
    protected function getCoremediaClientMock(
        CoremediaApiResponseTransfer $coreMediaApiResponseTransfer
    ): CoremediaClientInterface {
        $coreMediaClientMock = $this->getMockBuilder(CoremediaClientInterface::class)
            ->setMethods(['getDocumentFragment'])
            ->getMock();
        $coreMediaClientMock->method('getDocumentFragment')->willReturn($coreMediaApiResponseTransfer);

        return $coreMediaClientMock;
    }

    /**
     * @param string $externalSeoSegment
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected function getUrlGeneratorMock(string $externalSeoSegment): UrlGeneratorInterface
    {
        $urlGeneratorMock = $this->getMockBuilder(UrlGeneratorInterface::class)
            ->setMethods(['generate'])
            ->getMockForAbstractClass();
        $urlGeneratorMock->method('generate')->willReturn(
            $externalSeoSegment === static::EXISTENT_ROUTE ? '/' . static::EXISTENT_ROUTE : ''
        );

        return $urlGeneratorMock;
    }

    /**
     * @return string[][]
     */
    public function correctApiResponseDataProvider(): array
    {
        return [
            [static::API_RESPONSE_CORRECT_DATA],
            [html_entity_decode(static::API_RESPONSE_CORRECT_DATA)],
        ];
    }

    /**
     * @return string[][]
     */
    public function incorrectApiResponseDataProvider(): array
    {
        return [
            [static::API_RESPONSE_INCORRECT_DATA],
            [html_entity_decode(static::API_RESPONSE_INCORRECT_DATA)],
        ];
    }

    /**
     * @return string[][]
     */
    public function nonexistentPlaceholderObjectTypeApiResponseDataProvider(): array
    {
        return [
            [static::API_RESPONSE_NONEXISTENT_PLACEHOLDER_OBJECT_TYPE],
            [html_entity_decode(static::API_RESPONSE_NONEXISTENT_PLACEHOLDER_OBJECT_TYPE)],
        ];
    }
}
