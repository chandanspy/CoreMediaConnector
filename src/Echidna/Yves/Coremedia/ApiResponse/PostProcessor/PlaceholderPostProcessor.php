<?php



namespace Echidna\Yves\Coremedia\ApiResponse\PostProcessor;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;
use Echidna\Yves\Coremedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutorInterface;
use Echidna\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface;

class PlaceholderPostProcessor implements PlaceholderPostProcessorInterface
{
    /**
     * @var \Echidna\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface[]
     */
    protected $placeholderReplacementRenderers;

    /**
     * @var \Echidna\Yves\Coremedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutorInterface
     */
    protected $incorrectPlaceholderDataExecutor;

    /**
     * @param \Echidna\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface[] $placeholderReplacementRenderers
     * @param \Echidna\Yves\Coremedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutorInterface $incorrectPlaceholderDataExecutor
     */
    public function __construct(
        array $placeholderReplacementRenderers,
        IncorrectPlaceholderDataExecutorInterface $incorrectPlaceholderDataExecutor
    ) {
        $this->placeholderReplacementRenderers = $placeholderReplacementRenderers;
        $this->incorrectPlaceholderDataExecutor = $incorrectPlaceholderDataExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoremediaPlaceholderTransfer
     */
    public function addReplacement(
        CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): CoremediaPlaceholderTransfer {
        $placeholderReplacementRenderer = $this->resolvePlaceholderReplacementRenderer($coreMediaPlaceholderTransfer);

        if (!$placeholderReplacementRenderer) {
            return $coreMediaPlaceholderTransfer;
        }

        $placeholderReplacement = $placeholderReplacementRenderer->getPlaceholderReplacement(
            $coreMediaPlaceholderTransfer,
            $locale
        );

        if (!$placeholderReplacement) {
            $this->incorrectPlaceholderDataExecutor->executeIncorrectPlaceholderData($coreMediaPlaceholderTransfer, $locale);

            return $this->setFallbackPlaceholderReplacement($placeholderReplacementRenderer, $coreMediaPlaceholderTransfer);
        }

        $coreMediaPlaceholderTransfer->setPlaceholderReplacement($placeholderReplacement);

        return $coreMediaPlaceholderTransfer;
    }

    /**
     * @param \Echidna\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface $placeholderReplacementRenderer
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return \Generated\Shared\Transfer\CoremediaPlaceholderTransfer
     */
    protected function setFallbackPlaceholderReplacement(
        PlaceholderReplacementRendererInterface $placeholderReplacementRenderer,
        CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
    ): CoremediaPlaceholderTransfer {
        return $coreMediaPlaceholderTransfer->setPlaceholderReplacement(
            $placeholderReplacementRenderer->getFallbackPlaceholderReplacement()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return \Echidna\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface|null
     */
    protected function resolvePlaceholderReplacementRenderer(
        CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
    ): ?PlaceholderReplacementRendererInterface {
        foreach ($this->placeholderReplacementRenderers as $placeholderReplacementRenderer) {
            if ($placeholderReplacementRenderer->isApplicable($coreMediaPlaceholderTransfer)) {
                return $placeholderReplacementRenderer;
            }
        }

        return null;
    }
}
