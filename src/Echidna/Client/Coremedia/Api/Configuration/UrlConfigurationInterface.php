<?php



namespace Echidna\Client\Coremedia\Api\Configuration;

interface UrlConfigurationInterface
{
    /**
     * @return string
     */
    public function getCoremediaHost(): string;

    /**
     * @return string
     */
    public function getBasePath(): string;

    /**
     * @param string $storeName
     *
     * @return string
     */
    public function getStore(string $storeName): string;

    /**
     * @param string $store
     * @param string $localeName
     *
     * @return string
     */
    public function getLocale(string $store, string $localeName): string;
}
