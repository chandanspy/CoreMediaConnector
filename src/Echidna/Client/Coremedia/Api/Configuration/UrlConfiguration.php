<?php



namespace Echidna\Client\Coremedia\Api\Configuration;

use Echidna\Client\Coremedia\Api\Exception\UrlConfigurationException;
use Echidna\Client\Coremedia\CoremediaConfig;

class UrlConfiguration implements UrlConfigurationInterface
{
    /**
     * @var \Echidna\Client\Coremedia\CoremediaConfig
     */
    protected $config;

    /**
     * @param \Echidna\Client\Coremedia\CoremediaConfig $config
     */
    public function __construct(CoremediaConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @throws \Echidna\Client\Coremedia\Api\Exception\UrlConfigurationException
     *
     * @return string
     */
    public function getCoremediaHost(): string
    {
        $coreMediaHost = $this->config->getCoremediaHost();

        if (!$coreMediaHost) {
            throw new UrlConfigurationException('Please specify the Coremedia host in configuration.');
        }

        return $coreMediaHost;
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->config->getFragmentBasePath();
    }

    /**
     * @param string $storeName
     *
     * @throws \Echidna\Client\Coremedia\Api\Exception\UrlConfigurationException
     *
     * @return string
     */
    public function getStore(string $storeName): string
    {
        $applicationStoreMapping = $this->config->getApplicationStoreMapping();

        if (!isset($applicationStoreMapping[$storeName])) {
            throw new UrlConfigurationException(
                sprintf('Cannot find storeId by store name "%s" in application store mapping.', $storeName)
            );
        }

        return $applicationStoreMapping[$storeName];
    }

    /**
     * @param string $store
     * @param string $localeName
     *
     * @throws \Echidna\Client\Coremedia\Api\Exception\UrlConfigurationException
     *
     * @return string
     */
    public function getLocale(string $store, string $localeName): string
    {
        $applicationStoreLocaleMapping = $this->config->getApplicationStoreLocaleMapping();

        if (!isset($applicationStoreLocaleMapping[$store])) {
            throw new UrlConfigurationException(
                sprintf('Not defined storeId "%s" in application store locale mapping.', $store)
            );
        }

        if (!isset($applicationStoreLocaleMapping[$store][$localeName])) {
            throw new UrlConfigurationException(
                sprintf(
                    'Cannot find locale by locale name "%s" for storeId "%s" in application store locale mapping.',
                    $localeName,
                    $store
                )
            );
        }

        return $applicationStoreLocaleMapping[$store][$localeName];
    }
}
