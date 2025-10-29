<?php

namespace Gone\Funding\Block\Product\View;

use Gone\Funding\Helper\FundingHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Helper\Product;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Json\EncoderInterface as JsonEncoder;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\EncoderInterface;

/**
 * Class FundingSimulator
 * @package Gone\Funding\Block\Product\View
 */
class FundingSimulator extends View implements IdentityInterface
{
    public const CONDITIONS_PAGE = 'financement';

    protected FundingHelper $_fundingHelper;
    protected Json $_jsonSerializer;

    public function __construct(
        Context $context,
        EncoderInterface $urlEncoder,
        JsonEncoder $jsonEncoder,
        StringUtils $string,
        Product $productHelper,
        ConfigInterface $productTypeConfig,
        FormatInterface $localeFormat,
        Session $customerSession,
        ProductRepositoryInterface $productRepository,
        PriceCurrencyInterface $priceCurrency,
        FundingHelper $fundingHelper,
        Json $jsonSerializer,
        array $data = []
    )
    {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
        $this->_fundingHelper = $fundingHelper;
        $this->_jsonSerializer = $jsonSerializer;
    }

    /**
     * @return string|bool
     */
    public function isFundingAuthorized()
    {
        $ratios = $this->_fundingHelper->getRatios();
        $price = $this->getProductPriceHt();

        if (!empty($ratios)) {
            foreach ($ratios as $ratio) {
                if ($ratio['value_mini'] <= $price && $price <= $ratio['value_max']) {
                    if ($notSetKey = array_search('0', $ratio)) {
                        unset($ratio[$notSetKey]);
                    }

                    $ratio = $this->cleanRatioData($ratio);

                    return $this->_jsonSerializer->serialize($ratio);

                }
            }
        }
        return false;
    }

    /**
     * @return float
     */
    public function getProductPriceHt()
    {
        return $this->getProduct()->getPriceInfo()->getPrice('final_price')->getAmount()->getBaseAmount();
    }

    /**
     * @param $ratio
     * @return array
     */
    protected function cleanRatioData($ratio)
    {
        $months = $this->_fundingHelper->getMonthsDurationFlat();

        foreach ($ratio as $key => $value) {
            $keyData = explode('_', $key);
            if ($keyData[0] == "duration" && !in_array($keyData[1], $months)) {
                unset($ratio[$key]);
            }
        }
        return $ratio;
    }

    public function formatPrice($price)
    {
        return $this->priceCurrency->format($price, null, 2, "EUR");
    }
}
