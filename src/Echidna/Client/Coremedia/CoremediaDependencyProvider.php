<?php



namespace Echidna\Client\Coremedia;

use GuzzleHttp\Client;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Echidna\Client\Coremedia\Dependency\Guzzle\CoremediaToGuzzleBridge;

class CoremediaDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_GUZZLE = 'CLIENT_GUZZLE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addGuzzleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addGuzzleClient(Container $container): Container
    {
        $container->set(static::CLIENT_GUZZLE, function () {
            return new CoremediaToGuzzleBridge($this->createGuzzleHttpClient());
        });

        return $container;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function createGuzzleHttpClient(): Client
    {
        return new Client();
    }
}
