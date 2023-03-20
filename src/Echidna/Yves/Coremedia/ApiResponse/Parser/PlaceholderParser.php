<?php



namespace Echidna\Yves\Coremedia\ApiResponse\Parser;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;
use Echidna\Shared\Coremedia\CoremediaConfig as SharedCoremediaConfig;
use Echidna\Yves\Coremedia\CoremediaConfig;
use Echidna\Yves\Coremedia\Dependency\Service\CoremediaToUtilEncodingServiceInterface;

class PlaceholderParser implements PlaceholderParserInterface
{
    /**
     * @var \Echidna\Yves\Coremedia\Dependency\Service\CoremediaToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Echidna\Yves\Coremedia\CoremediaConfig
     */
    protected $config;

    /**
     * @param \Echidna\Yves\Coremedia\Dependency\Service\CoremediaToUtilEncodingServiceInterface $utilEncodingService
     * @param \Echidna\Yves\Coremedia\CoremediaConfig $config
     */
    public function __construct(
        CoremediaToUtilEncodingServiceInterface $utilEncodingService,
        CoremediaConfig $config
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->config = $config;
    }

    /**
     * @param string $content
     *
     * @return \Generated\Shared\Transfer\CoremediaPlaceholderTransfer[]
     */
    public function parse(string $content): array
    {
        preg_match_all(
            $this->config->getPlaceholderPattern(),
            $content,
            $matches
        );

        $placeholders = [];

        if (!$matches[SharedCoremediaConfig::PREG_MATCH_PLACEHOLDER_KEY]) {
            return [];
        }

        $placeholdersData = array_unique($matches[SharedCoremediaConfig::PREG_MATCH_PLACEHOLDER_KEY]);

        foreach ($placeholdersData as $placeholderKey => $placeholderData) {
            $decodedPlaceholderData = $this->decodePlaceholderData($placeholderData);

            if (!$decodedPlaceholderData) {
                continue;
            }

            $coreMediaPlaceholderTransfer = (new CoremediaPlaceholderTransfer())
                ->fromArray($decodedPlaceholderData, true)
                ->setPlaceholderBody($matches[0][$placeholderKey]);

            $placeholders[] = $coreMediaPlaceholderTransfer;
        }

        return $placeholders;
    }

    /**
     * @param string $placeholderData
     *
     * @return array|null
     */
    protected function decodePlaceholderData(string $placeholderData): ?array
    {
        return $this->utilEncodingService->decodeJson(
            $this->htmlEntityDecodePlaceholderData($placeholderData),
            true
        );
    }

    /**
     * @param string $placeholderData
     *
     * @return string
     */
    protected function htmlEntityDecodePlaceholderData(string $placeholderData): string
    {
        return html_entity_decode($placeholderData, ENT_QUOTES, 'UTF-8');
    }
}
