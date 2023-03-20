<?php



namespace Echidna\Client\Coremedia;

use Spryker\Client\Kernel\AbstractFactory;
use Echidna\Client\Coremedia\Api\ApiClient;
use Echidna\Client\Coremedia\Api\ApiClientInterface;
use Echidna\Client\Coremedia\Api\Builder\RequestBuilder;
use Echidna\Client\Coremedia\Api\Builder\RequestBuilderInterface;
use Echidna\Client\Coremedia\Api\Builder\UrlBuilder;
use Echidna\Client\Coremedia\Api\Builder\UrlBuilderInterface;
use Echidna\Client\Coremedia\Api\Configuration\UrlConfiguration;
use Echidna\Client\Coremedia\Api\Configuration\UrlConfigurationInterface;
use Echidna\Client\Coremedia\Api\Executor\RequestExecutor;
use Echidna\Client\Coremedia\Api\Executor\RequestExecutorInterface;
use Echidna\Client\Coremedia\Dependency\Guzzle\CoremediaToGuzzleInterface;

/**
 * @method \Echidna\Client\Coremedia\CoremediaConfig getConfig()
 */
class CoremediaFactory extends AbstractFactory
{
    /**
     * @return \Echidna\Client\Coremedia\Api\ApiClientInterface
     */
    public function createApiClient(): ApiClientInterface
    {
        return new ApiClient(
            $this->createApiRequestBuilder(),
            $this->createApiRequestExecutor(),
            $this->createUrlBuilder()
        );
    }

    /**
     * @return \Echidna\Client\Coremedia\Api\Builder\RequestBuilderInterface
     */
    public function createApiRequestBuilder(): RequestBuilderInterface
    {
        return new RequestBuilder();
    }

    /**
     * @return \Echidna\Client\Coremedia\Api\Executor\RequestExecutorInterface
     */
    public function createApiRequestExecutor(): RequestExecutorInterface
    {
        return new RequestExecutor(
            $this->getGuzzleClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Echidna\Client\Coremedia\Api\Builder\UrlBuilderInterface
     */
    public function createUrlBuilder(): UrlBuilderInterface
    {
        return new UrlBuilder(
            $this->createUrlConfiguration()
        );
    }

    /**
     * @return \Echidna\Client\Coremedia\Api\Configuration\UrlConfigurationInterface
     */
    public function createUrlConfiguration(): UrlConfigurationInterface
    {
        return new UrlConfiguration($this->getConfig());
    }

    /**
     * @return \Echidna\Client\Coremedia\Dependency\Guzzle\CoremediaToGuzzleInterface
     */
    public function getGuzzleClient(): CoremediaToGuzzleInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::CLIENT_GUZZLE);
    }
}
