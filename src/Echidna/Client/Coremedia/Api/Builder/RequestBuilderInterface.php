<?php



namespace Echidna\Client\Coremedia\Api\Builder;

use Psr\Http\Message\RequestInterface;

interface RequestBuilderInterface
{
    /**
     * @param string $requestMethod
     * @param string $requestUrl
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    public function buildRequest(
        string $requestMethod,
        string $requestUrl
    ): RequestInterface;
}
