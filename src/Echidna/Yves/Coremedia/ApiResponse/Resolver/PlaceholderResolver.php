<?php



namespace Echidna\Yves\Coremedia\ApiResponse\Resolver;

use Generated\Shared\Transfer\CoremediaApiResponseTransfer;
use Echidna\Yves\Coremedia\ApiResponse\Parser\PlaceholderParserInterface;
use Echidna\Yves\Coremedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface;
use Echidna\Yves\Coremedia\ApiResponse\Replacer\PlaceholderReplacerInterface;

class PlaceholderResolver implements ApiResponseResolverInterface
{
    /**
     * @var \Echidna\Yves\Coremedia\ApiResponse\Parser\PlaceholderParserInterface
     */
    protected $placeholderParser;

    /**
     * @var \Echidna\Yves\Coremedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface
     */
    protected $placeholderPostProcessor;

    /**
     * @var \Echidna\Yves\Coremedia\ApiResponse\Replacer\PlaceholderReplacerInterface
     */
    protected $placeholderReplacer;

    /**
     * @param \Echidna\Yves\Coremedia\ApiResponse\Parser\PlaceholderParserInterface $placeholderParser
     * @param \Echidna\Yves\Coremedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface $placeholderPostProcessor
     * @param \Echidna\Yves\Coremedia\ApiResponse\Replacer\PlaceholderReplacerInterface $placeholderReplacer
     */
    public function __construct(
        PlaceholderParserInterface $placeholderParser,
        PlaceholderPostProcessorInterface $placeholderPostProcessor,
        PlaceholderReplacerInterface $placeholderReplacer
    ) {
        $this->placeholderParser = $placeholderParser;
        $this->placeholderPostProcessor = $placeholderPostProcessor;
        $this->placeholderReplacer = $placeholderReplacer;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaApiResponseTransfer $coreMediaApiResponseTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function resolve(
        CoremediaApiResponseTransfer $coreMediaApiResponseTransfer,
        string $locale
    ): CoremediaApiResponseTransfer {
        $coreMediaPlaceholderTransfers = $this->placeholderParser->parse($coreMediaApiResponseTransfer->getData());

        if (!$coreMediaPlaceholderTransfers) {
            return $coreMediaApiResponseTransfer;
        }

        foreach ($coreMediaPlaceholderTransfers as $coreMediaPlaceholderTransfer) {
            $coreMediaPlaceholderTransfer = $this->placeholderPostProcessor->addReplacement(
                $coreMediaPlaceholderTransfer,
                $locale
            );
            $coreMediaApiResponseTransfer->setData(
                $this->placeholderReplacer->replace(
                    $coreMediaApiResponseTransfer->getData(),
                    $coreMediaPlaceholderTransfer
                )
            );
        }

        return $coreMediaApiResponseTransfer;
    }
}
