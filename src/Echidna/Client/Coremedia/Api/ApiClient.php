<?php



namespace Echidna\Client\Coremedia\Api;

use Generated\Shared\Transfer\CoremediaApiResponseTransfer;
use Generated\Shared\Transfer\CoremediaFragmentRequestTransfer;
use Echidna\Client\Coremedia\Api\Builder\RequestBuilderInterface;
use Echidna\Client\Coremedia\Api\Builder\UrlBuilderInterface;
use Echidna\Client\Coremedia\Api\Executor\RequestExecutorInterface;

class ApiClient implements ApiClientInterface
{
    protected const REQUEST_GET_METHOD = 'GET';

    /**
     * @var \Echidna\Client\Coremedia\Api\Builder\RequestBuilderInterface
     */
    protected $requestBuilder;

    /**
     * @var \Echidna\Client\Coremedia\Api\Executor\RequestExecutorInterface
     */
    protected $requestExecutor;

    /**
     * @var \Echidna\Client\Coremedia\Api\Builder\UrlBuilderInterface
     */
    protected $urlBuilder;

    /**
     * @param \Echidna\Client\Coremedia\Api\Builder\RequestBuilderInterface $requestBuilder
     * @param \Echidna\Client\Coremedia\Api\Executor\RequestExecutorInterface $requestExecutor
     * @param \Echidna\Client\Coremedia\Api\Builder\UrlBuilderInterface $urlBuilder
     */
    public function __construct(
        RequestBuilderInterface $requestBuilder,
        RequestExecutorInterface $requestExecutor,
        UrlBuilderInterface $urlBuilder
    ) {
        $this->requestBuilder = $requestBuilder;
        $this->requestExecutor = $requestExecutor;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function getDocumentFragment(
        CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): CoremediaApiResponseTransfer {
        $request = $this->requestBuilder->buildRequest(
            static::REQUEST_GET_METHOD,
            $this->urlBuilder->buildDocumentFragmentApiUrl($coreMediaFragmentRequestTransfer)
        );

        return $this->requestExecutor->execute($request);
    }
}
