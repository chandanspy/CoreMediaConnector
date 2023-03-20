<?php



namespace EchidnaTest\Client\Coremedia\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CoremediaFragmentRequestBuilder;
use Generated\Shared\Transfer\CoremediaFragmentRequestTransfer;

class CoremediaHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CoremediaFragmentRequestTransfer
     */
    public function getCoremediaFragmentRequestTransfer(array $seedData = []): CoremediaFragmentRequestTransfer
    {
        return (new CoremediaFragmentRequestBuilder($seedData))->build();
    }
}
