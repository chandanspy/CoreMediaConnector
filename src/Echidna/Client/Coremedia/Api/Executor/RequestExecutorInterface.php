<?php



namespace Echidna\Client\Coremedia\Api\Executor;

use Generated\Shared\Transfer\CoremediaApiResponseTransfer;
use Psr\Http\Message\RequestInterface;

interface RequestExecutorInterface
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function execute(RequestInterface $request): CoremediaApiResponseTransfer;
}
