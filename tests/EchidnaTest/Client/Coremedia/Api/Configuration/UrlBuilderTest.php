<?php



namespace EchidnaTest\Client\Coremedia\Api\Configuration;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CoremediaFragmentRequestTransfer;
use Echidna\Client\Coremedia\Api\Exception\UrlConfigurationException;
use Echidna\Client\Coremedia\CoremediaConfig;
use Echidna\Client\Coremedia\CoremediaFactory;

class UrlBuilderTest extends Unit
{
    protected const COREMEDIA_HOST = 'https://test.coremedia.com';
    protected const APPLICATION_STORE_MAPPING = [
        'DE' => 'test-store',
    ];
    protected const APPLICATION_STORE_LOCALE_MAPPING = [
        'test-store' => [
            'en_US' => 'en-GB',
        ],
    ];

    /**
     * @var \EchidnaTest\Client\Coremedia\CoremediaClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUrlBuilderProvidesCorrectUrlForApiRequest(): void
    {
        $coreMediaFragmentRequestTransfer = $this->tester->getCoremediaFragmentRequestTransfer([
            CoremediaFragmentRequestTransfer::STORE => 'DE',
            CoremediaFragmentRequestTransfer::LOCALE => 'en_US',
            CoremediaFragmentRequestTransfer::PRODUCT_ID => 111,
            CoremediaFragmentRequestTransfer::CATEGORY_ID => 222,
            CoremediaFragmentRequestTransfer::PAGE_ID => 'test-page',
            CoremediaFragmentRequestTransfer::PLACEMENT => 'header',
            CoremediaFragmentRequestTransfer::VIEW => 'asDefaultFragment',
        ]);

        $coreMediaFactoryMock = $this->getCoremediaFactoryMock();
        $urlBuilder = $coreMediaFactoryMock->createUrlBuilder();
        $url = $urlBuilder->buildDocumentFragmentApiUrl($coreMediaFragmentRequestTransfer);

        $this->assertEquals('https://test.coremedia.com/blueprint/servlet/service/fragment/test-store/en-GB/' .
            'params;productId=111;categoryId=222;pageId=test-page;' .
            'placement=header;view=asDefaultFragment', $url);
    }

    /**
     * @return void
     */
    public function testUrlBuilderProvidesCorrectUrlOnNullParametersProvided(): void
    {
        $coreMediaFragmentRequestTransfer = $this->tester->getCoremediaFragmentRequestTransfer([
            CoremediaFragmentRequestTransfer::STORE => 'DE',
            CoremediaFragmentRequestTransfer::LOCALE => 'en_US',
            CoremediaFragmentRequestTransfer::PRODUCT_ID => null,
            CoremediaFragmentRequestTransfer::CATEGORY_ID => 222,
            CoremediaFragmentRequestTransfer::PAGE_ID => null,
            CoremediaFragmentRequestTransfer::PLACEMENT => null,
            CoremediaFragmentRequestTransfer::VIEW => null,
        ]);

        $coreMediaFactoryMock = $this->getCoremediaFactoryMock();
        $urlBuilder = $coreMediaFactoryMock->createUrlBuilder();
        $url = $urlBuilder->buildDocumentFragmentApiUrl($coreMediaFragmentRequestTransfer);

        $this->assertEquals('https://test.coremedia.com/blueprint/servlet/service/fragment/test-store/en-GB/' .
            'params;categoryId=222', $url);
    }

    /**
     * @return void
     */
    public function testUrlBuilderFailsOnIncorrectStoreProvided(): void
    {
        $coreMediaFragmentRequestTransfer = $this->tester->getCoremediaFragmentRequestTransfer([
            CoremediaFragmentRequestTransfer::STORE => 'wrong-store',
            CoremediaFragmentRequestTransfer::LOCALE => 'en_US',
            CoremediaFragmentRequestTransfer::PRODUCT_ID => 111,
            CoremediaFragmentRequestTransfer::CATEGORY_ID => 222,
            CoremediaFragmentRequestTransfer::PAGE_ID => 'test-page',
            CoremediaFragmentRequestTransfer::PLACEMENT => 'header',
            CoremediaFragmentRequestTransfer::VIEW => 'asDefaultFragment',
        ]);

        $coreMediaFactoryMock = $this->getCoremediaFactoryMock();
        $urlBuilder = $coreMediaFactoryMock->createUrlBuilder();

        $this->expectException(UrlConfigurationException::class);
        $urlBuilder->buildDocumentFragmentApiUrl($coreMediaFragmentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUrlBuilderFailsOnIncorrectLocaleProvided(): void
    {
        $coreMediaFragmentRequestTransfer = $this->tester->getCoremediaFragmentRequestTransfer([
            CoremediaFragmentRequestTransfer::STORE => 'DE',
            CoremediaFragmentRequestTransfer::LOCALE => 'wrong_locale',
            CoremediaFragmentRequestTransfer::PRODUCT_ID => 111,
            CoremediaFragmentRequestTransfer::CATEGORY_ID => 222,
            CoremediaFragmentRequestTransfer::PAGE_ID => 'test-page',
            CoremediaFragmentRequestTransfer::PLACEMENT => 'header',
            CoremediaFragmentRequestTransfer::VIEW => 'asDefaultFragment',
        ]);

        $coreMediaFactoryMock = $this->getCoremediaFactoryMock();
        $urlBuilder = $coreMediaFactoryMock->createUrlBuilder();

        $this->expectException(UrlConfigurationException::class);
        $urlBuilder->buildDocumentFragmentApiUrl($coreMediaFragmentRequestTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Echidna\Client\Coremedia\CoremediaConfig
     */
    protected function getCoremediaConfigMock(): CoremediaConfig
    {
        $coreMediaConfigMock = $this->getMockBuilder(CoremediaConfig::class)
            ->setMethods(['getCoremediaHost', 'getApplicationStoreMapping', 'getApplicationStoreLocaleMapping'])
            ->getMock();
        $coreMediaConfigMock->method('getCoremediaHost')->willReturn(static::COREMEDIA_HOST);
        $coreMediaConfigMock->method('getApplicationStoreMapping')
            ->willReturn(static::APPLICATION_STORE_MAPPING);
        $coreMediaConfigMock->method('getApplicationStoreLocaleMapping')
            ->willReturn(static::APPLICATION_STORE_LOCALE_MAPPING);

        return $coreMediaConfigMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Echidna\Client\Coremedia\CoremediaFactory
     */
    protected function getCoremediaFactoryMock(): CoremediaFactory
    {
        $coreMediaFactoryMock = $this->getMockBuilder(CoremediaFactory::class)
            ->setMethods(['getConfig'])
            ->getMock();
        $coreMediaFactoryMock->method('getConfig')->willReturn($this->getCoremediaConfigMock());

        return $coreMediaFactoryMock;
    }
}
