<?php
/*
 *
 * @copyright Copyright © 2020 410-Gone. All rights reserved.
 * @author    contact@410-gone.fr
 *
 */

namespace Gone\Cloaking\Setup\Patch\Data;

use Exception;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Registry;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class Mail implements DataPatchInterface
{
    protected $escaper;
    protected $transportBuilder;
    protected $_state;
    protected $_registry;
    protected $_scopeConfig;
    protected $_storeManager;
    protected $_urlInterface;

    public function __construct(
        Escaper $escaper,
        TransportBuilder $transportBuilder,
        State $state,
        Registry $registry,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        UrlInterface $urlInterface
    ) {
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->_registry = $registry;
        $this->_state = $state;
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->_urlInterface = $urlInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {

        if ($this->_registry->registry('isSecureArea') == null) {
            $this->_registry->register('isSecureArea', true);
        }
        try {
            $this->_state->setAreaCode(FrontNameResolver::AREA_CODE);
        } catch (Exception $e) {

        }

        try {

            $sender = [
                'name' => $this->escaper->escapeHtml('Cloaking install'),
                'email' => $this->getStoreEmail()
            ];

            $transport = $this->transportBuilder
                ->setTemplateIdentifier('email')
                ->setTemplateOptions(['area' => 'frontend', 'store' => Store::DEFAULT_STORE_ID])
                ->setTemplateVars($this->_getDataEmailTemplate())
                ->setFrom($sender)
                ->addTo('contact@410-gone.fr')
                ->getTransport();
            $transport->sendMessage();
        } catch (Exception $e) {
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [

        ];
    }

    public function getStoreEmail()
    {
           return $this->_scopeConfig->getValue(
               'trans_email/ident_general/email',
               ScopeInterface::SCOPE_WEBSITE
           );
    }

    protected function _getDataEmailTemplate()
    {
        $tb = [
            'siteUrl' => $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_WEB),
        ];
        return $tb;
    }
}
