<?php



namespace Echidna\Client\Coremedia\Api\Builder;

use Generated\Shared\Transfer\CoremediaFragmentRequestTransfer;
use Echidna\Client\Coremedia\Api\Configuration\UrlConfigurationInterface;

class UrlBuilder implements UrlBuilderInterface
{
    protected const HTTP_QUERY_KEY_VALUE_PATTERN = '%s=%s';

    protected const NAME_QUERY_PARAMS = [
        CoremediaFragmentRequestTransfer::PAGE_ID,
        CoremediaFragmentRequestTransfer::PRODUCT_ID,
        CoremediaFragmentRequestTransfer::CATEGORY_ID,
        CoremediaFragmentRequestTransfer::VIEW,
        CoremediaFragmentRequestTransfer::PLACEMENT,
    ];

    /**
     * @var \Echidna\Client\Coremedia\Api\Configuration\UrlConfigurationInterface
     */
    protected $urlConfiguration;

    /**
     * @param \Echidna\Client\Coremedia\Api\Configuration\UrlConfigurationInterface $urlConfiguration
     */
    public function __construct(UrlConfigurationInterface $urlConfiguration)
    {
        $this->urlConfiguration = $urlConfiguration;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return string
     */
    public function buildDocumentFragmentApiUrl(
        CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): string {
        $query = $this->transformCoremediaFragmentRequestTransferPropertiesToQueryString(
            $coreMediaFragmentRequestTransfer
        );

        return $this->urlConfiguration->getCoremediaHost() . $this->urlConfiguration->getBasePath() . $query;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return string
     */
    protected function transformCoremediaFragmentRequestTransferPropertiesToQueryString(
        CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): string {
        $store = $this->urlConfiguration->getStore($coreMediaFragmentRequestTransfer->getStore());
        $locale = $this->urlConfiguration->getLocale($store, $coreMediaFragmentRequestTransfer->getLocale());
        $coreMediaFragmentRequestTransferArray = $coreMediaFragmentRequestTransfer->toArray(true, true);
        $queryParams = [];

        foreach ($coreMediaFragmentRequestTransferArray as $key => $value) {
            if (in_array($key, static::NAME_QUERY_PARAMS, true)) {
                $queryParams[] = $this->serializeCoremediaFragmentRequestTransferProperty($key, $value);
            }
        }

        return sprintf(
            '%s/%s/params;%s',
            $store,
            $locale,
            implode(';', array_filter($queryParams))
        );
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return string
     */
    protected function serializeCoremediaFragmentRequestTransferProperty(string $key, $value): string
    {
        if (is_bool($value)) {
            return sprintf(static::HTTP_QUERY_KEY_VALUE_PATTERN, $key, var_export($value, true));
        }

        if (is_scalar($value)) {
            return sprintf(static::HTTP_QUERY_KEY_VALUE_PATTERN, $key, $value);
        }

        return '';
    }
}
