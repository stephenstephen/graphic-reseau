<?php

namespace Netreviews\Avisverifies\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;

class WidgetFloat extends Template
{
    public $storeManager;
    public $isEnableScriptfloat;
    public $scriptfloat;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * WidgetFloat constructor.
     * @param Context $context
     * @throws NoSuchEntityException
     */
    public function __construct(
        Context $context
    ) {

        $this->scopeConfig = $context->getScopeConfig();
        $this->storeManager = $context->getStoreManager();
        parent::__construct($context);
        $this->setScriptfloatEnable();
    }

    /**
     * récupère le script pour afficher la widget avis-verifiés
     * @throws NoSuchEntityException
     */
    private function setScriptfloatEnable()
    {
        $storeId =  $this->storeManager->getStore()->getId();
        $this->scriptfloat = $this->scopeConfig->getValue(
            'av_configuration/plateforme/scriptfloat',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $scriptfloatAllowed = $this->scopeConfig->getValue(
            'av_configuration/plateforme/scriptfloat_allowed',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $enabledwebsite = $this->scopeConfig->getValue(
            'av_configuration/system_integration/enabledwebsite',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $this->scriptfloat = str_replace(">", "&avClose;", str_replace("<", "&avOpen;", str_replace("'", "", trim($this->scriptfloat))));
        if ($enabledwebsite == '1'
            && $scriptfloatAllowed == 'yes'
            && !empty($this->scriptfloat)
        ) {
            $this->isEnableScriptfloat = 1;
        } else {
            $this->isEnableScriptfloat =  0;
        }
    }

    protected function getCacheLifetime()
    {
        return 3600;
    }
}
