<?php
namespace Netreviews\Avisverifies\Block\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Netreviews\Avisverifies\Model\Api\NetreviewsManagement;

class CheckSandbox extends Field
{

    /**
     * @var string
     */
    protected $_template = 'Netreviews_Avisverifies::system/config/CheckSandbox.phtml';

    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Remove scope label
     *
     * @param  AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        return parent::render($element);
    }

    /**
     * Return element html
     *
     * @param  AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Return url for sandbox button
     *
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->_scopeConfig->getValue(
            NetreviewsManagement::XML_PATH_URL_SANDBOX,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'check_sandbox_button',
                'label' => __('Check url'),
            ]
        );

        return $button->toHtml();
    }

}