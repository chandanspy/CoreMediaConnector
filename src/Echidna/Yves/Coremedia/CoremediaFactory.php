<?php



namespace Echidna\Yves\Coremedia;

use Spryker\Yves\Kernel\AbstractFactory;
use Echidna\Yves\Coremedia\ApiResponse\ApiResponsePreparator;
use Echidna\Yves\Coremedia\ApiResponse\ApiResponsePreparatorInterface;
use Echidna\Yves\Coremedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutor;
use Echidna\Yves\Coremedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutorInterface;
use Echidna\Yves\Coremedia\ApiResponse\Parser\PlaceholderParser;
use Echidna\Yves\Coremedia\ApiResponse\Parser\PlaceholderParserInterface;
use Echidna\Yves\Coremedia\ApiResponse\PostProcessor\PlaceholderPostProcessor;
use Echidna\Yves\Coremedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface;
use Echidna\Yves\Coremedia\ApiResponse\Renderer\CategoryUrlPlaceholderReplacementRenderer;
use Echidna\Yves\Coremedia\ApiResponse\Renderer\CustomPageUrlPlaceholderReplacementRenderer;
use Echidna\Yves\Coremedia\ApiResponse\Renderer\PageMetadataPlaceholderReplacementRenderer;
use Echidna\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface;
use Echidna\Yves\Coremedia\ApiResponse\Renderer\ProductPricePlaceholderReplacementRenderer;
use Echidna\Yves\Coremedia\ApiResponse\Renderer\ProductUrlPlaceholderReplacementRenderer;
use Echidna\Yves\Coremedia\ApiResponse\Replacer\Metadata\DescriptionMetadataReplacer;
use Echidna\Yves\Coremedia\ApiResponse\Replacer\Metadata\KeywordsMetadataReplacer;
use Echidna\Yves\Coremedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface;
use Echidna\Yves\Coremedia\ApiResponse\Replacer\Metadata\PageNameMetadataReplacer;
use Echidna\Yves\Coremedia\ApiResponse\Replacer\Metadata\TitleMetadataReplacer;
use Echidna\Yves\Coremedia\ApiResponse\Replacer\PlaceholderReplacer;
use Echidna\Yves\Coremedia\ApiResponse\Replacer\PlaceholderReplacerInterface;
use Echidna\Yves\Coremedia\ApiResponse\Resolver\ApiResponseResolverInterface;
use Echidna\Yves\Coremedia\ApiResponse\Resolver\PlaceholderResolver;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToCategoryStorageClientInterface;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToMoneyClientInterface;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductClientInterface;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductStorageClientInterface;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToProductStorageClientInterface;
use Echidna\Yves\Coremedia\Dependency\Client\CoremediaToStoreClientInterface;
use Echidna\Yves\Coremedia\Dependency\Service\CoremediaToUtilEncodingServiceInterface;
use Echidna\Yves\Coremedia\Formatter\ProductPriceFormatter;
use Echidna\Yves\Coremedia\Formatter\ProductPriceFormatterInterface;
use Echidna\Yves\Coremedia\Mapper\ApiContextMapper;
use Echidna\Yves\Coremedia\Mapper\ApiContextMapperInterface;
use Echidna\Yves\Coremedia\Reader\CmsSlotContent\CmsSlotContentReader;
use Echidna\Yves\Coremedia\Reader\CmsSlotContent\CmsSlotContentReaderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @method \Echidna\Yves\Coremedia\CoremediaConfig getConfig()
 * @method \Echidna\Client\Coremedia\CoremediaClientInterface getClient()
 */
class CoremediaFactory extends AbstractFactory
{
    /**
     * @return \Echidna\Yves\Coremedia\Reader\CmsSlotContent\CmsSlotContentReaderInterface
     */
    public function createCmsSlotContentReader(): CmsSlotContentReaderInterface
    {
        return new CmsSlotContentReader(
            $this->getClient(),
            $this->createApiContextMapper(),
            $this->createApiResponsePreparator()
        );
    }

    /**
     * @return \Echidna\Yves\Coremedia\Mapper\ApiContextMapperInterface
     */
    public function createApiContextMapper(): ApiContextMapperInterface
    {
        return new ApiContextMapper();
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\ApiResponsePreparatorInterface
     */
    public function createApiResponsePreparator(): ApiResponsePreparatorInterface
    {
        return new ApiResponsePreparator(
            $this->getApiResponseResolvers()
        );
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Resolver\ApiResponseResolverInterface[]
     */
    public function getApiResponseResolvers(): array
    {
        return [
            $this->createPlaceholderResolver(),
        ];
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Resolver\ApiResponseResolverInterface
     */
    public function createPlaceholderResolver(): ApiResponseResolverInterface
    {
        return new PlaceholderResolver(
            $this->createPlaceholderParser(),
            $this->createPlaceholderPostProcessor(),
            $this->createPlaceholderReplacer()
        );
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Parser\PlaceholderParserInterface
     */
    public function createPlaceholderParser(): PlaceholderParserInterface
    {
        return new PlaceholderParser(
            $this->getUtilEncodingService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Replacer\PlaceholderReplacerInterface
     */
    public function createPlaceholderReplacer(): PlaceholderReplacerInterface
    {
        return new PlaceholderReplacer();
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface
     */
    public function createPlaceholderPostProcessor(): PlaceholderPostProcessorInterface
    {
        return new PlaceholderPostProcessor(
            $this->getPlaceholderReplacementRenderers(),
            $this->createIncorrectPlaceholderDataExecutor()
        );
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
     */
    public function createProductUrlPlaceholderReplacementRenderer(): PlaceholderReplacementRendererInterface
    {
        return new ProductUrlPlaceholderReplacementRenderer(
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
     */
    public function createCategoryUrlPlaceholderReplacementRenderer(): PlaceholderReplacementRendererInterface
    {
        return new CategoryUrlPlaceholderReplacementRenderer(
            $this->getCategoryStorageClient(),
            $this->getStoreClient()
        );
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
     */
    public function createPageMetadataPlaceholderReplacementRenderer(): PlaceholderReplacementRendererInterface
    {
        return new PageMetadataPlaceholderReplacementRenderer(
            $this->getMetadataReplacers()
        );
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
     */
    public function createProductPricePlaceholderReplacementRenderer(): PlaceholderReplacementRendererInterface
    {
        return new ProductPricePlaceholderReplacementRenderer(
            $this->getProductStorageClient(),
            $this->getPriceProductStorageClient(),
            $this->getPriceProductClient(),
            $this->createProductPriceFormatter()
        );
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
     */
    public function createCustomPageUrlPlaceholderReplacementRenderer(): PlaceholderReplacementRendererInterface
    {
        return new CustomPageUrlPlaceholderReplacementRenderer(
            $this->getUrlGenerator()
        );
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface[]
     */
    public function getPlaceholderReplacementRenderers(): array
    {
        return [
            $this->createProductUrlPlaceholderReplacementRenderer(),
            $this->createCategoryUrlPlaceholderReplacementRenderer(),
            $this->createPageMetadataPlaceholderReplacementRenderer(),
            $this->createProductPricePlaceholderReplacementRenderer(),
            $this->createCustomPageUrlPlaceholderReplacementRenderer(),
        ];
    }

    /**
     * @return \Echidna\Yves\Coremedia\Formatter\ProductPriceFormatterInterface
     */
    public function createProductPriceFormatter(): ProductPriceFormatterInterface
    {
        return new ProductPriceFormatter(
            $this->getMoneyClient()
        );
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface[]
     */
    public function getMetadataReplacers(): array
    {
        return [
            $this->createTitleMetadataReplacer(),
            $this->createDescriptionMetadataReplacer(),
            $this->createKeywordsMetadataReplacer(),
            $this->createPageNameMetadataReplacer(),
        ];
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface
     */
    public function createTitleMetadataReplacer(): MetadataReplacerInterface
    {
        return new TitleMetadataReplacer($this->getConfig());
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface
     */
    public function createDescriptionMetadataReplacer(): MetadataReplacerInterface
    {
        return new DescriptionMetadataReplacer($this->getConfig());
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface
     */
    public function createKeywordsMetadataReplacer(): MetadataReplacerInterface
    {
        return new KeywordsMetadataReplacer($this->getConfig());
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface
     */
    public function createPageNameMetadataReplacer(): MetadataReplacerInterface
    {
        return new PageNameMetadataReplacer($this->getConfig());
    }

    /**
     * @return \Echidna\Yves\Coremedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutorInterface
     */
    public function createIncorrectPlaceholderDataExecutor(): IncorrectPlaceholderDataExecutorInterface
    {
        return new IncorrectPlaceholderDataExecutor($this->getConfig());
    }

    /**
     * @return \Echidna\Yves\Coremedia\Dependency\Service\CoremediaToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): CoremediaToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToProductStorageClientInterface
     */
    public function getProductStorageClient(): CoremediaToProductStorageClientInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToCategoryStorageClientInterface
     */
    public function getCategoryStorageClient(): CoremediaToCategoryStorageClientInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::CLIENT_CATEGORY_STORAGE);
    }

    /**
     * @return \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): CoremediaToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }

    /**
     * @return \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductClientInterface
     */
    public function getPriceProductClient(): CoremediaToPriceProductClientInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::CLIENT_PRICE_PRODUCT);
    }

    /**
     * @return \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToMoneyClientInterface
     */
    public function getMoneyClient(): CoremediaToMoneyClientInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::CLIENT_MONEY);
    }

    /**
     * @return \Echidna\Yves\Coremedia\Dependency\Client\CoremediaToStoreClientInterface
     */
    public function getStoreClient(): CoremediaToStoreClientInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    public function getUrlGenerator(): UrlGeneratorInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::URL_GENERATOR);
    }
}
