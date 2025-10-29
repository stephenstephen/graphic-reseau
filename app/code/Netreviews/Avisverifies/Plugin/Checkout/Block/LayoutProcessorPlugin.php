<?php
namespace Netreviews\Avisverifies\Plugin\Checkout\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use \Magento\Store\Model\StoreManagerInterface;

class LayoutProcessorPlugin
{

    protected $scopeConfig;
    protected $storeManager;
    protected $storeId;
    protected $configPath;
    protected $collectConsent;
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
) {
   $this->scopeConfig = $scopeConfig;
   $this->configPath = "av_configuration/plateforme/collect_consent";

   $this->storeManager = $storeManager;
   $this->storeId =  $this->storeManager->getStore()->getId();

   $this->collectConsent = $this->scopeConfig->getValue($this->configPath,ScopeInterface::SCOPE_STORE, $this->storeId);
}


    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ) {
        if($this->collectConsent == "yes"){
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['before-form']['children']['av_send_review'] = [
                'component' => 'Magento_Ui/js/form/element/single-checkbox',
                'config' => [
                    'customScope' => 'shippingAddress.custom_attributes',
                    'customEntry' => null,
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/components/single/checkbox',
                    'options' => [],
                    'id' => 'av-send-review'
                ],
                'dataScope' => 'shippingAddress.custom_attributes.av_send_review',
                'label' => 'Ich bin damit einverstanden, nach meiner Bestellung eine Bewertungsanfrage von Echte Bewertungen zu erhalten.',
                'provider' => 'checkoutProvider',
                'visible' => true,
                'checked' => false,
                'value' => 1,
                'validation' => [
                    'required-entry' => false
                ],
                'sortOrder' => 250,
                'id' => 'av-send-review'
            ];
        } else {
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['before-form']['children']['av_send_review'] = [
                'component' => 'Magento_Ui/js/form/element/single-checkbox',
                'config' => [
                    'customScope' => 'shippingAddress.custom_attributes',
                    'customEntry' => null,
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/components/single/checkbox',
                    'options' => [],
                    'id' => 'av-send-review'
                ],
                'dataScope' => 'shippingAddress.custom_attributes.av_send_review',
                'label' => 'Ich bin damit einverstanden, nach meiner Bestellung eine Bewertungsanfrage von Echte Bewertungen zu erhalten.',
                'provider' => 'checkoutProvider',
                'visible' => false,
                'checked' => true,
                'value' => 1,
                'validation' => [
                    'required-entry' => false
                ],
                'sortOrder' => 250,
                'id' => 'av-send-review'
            ];
        }
        return $jsLayout;
    }
}