<?php



namespace Echidna\Yves\Coremedia\ApiResponse\Parser;

interface PlaceholderParserInterface
{
    /**
     * @param string $content
     *
     * @return \Generated\Shared\Transfer\CoremediaPlaceholderTransfer[]
     */
    public function parse(string $content): array;
}
