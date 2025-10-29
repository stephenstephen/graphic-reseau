<?php
namespace Magedelight\Productpdf\Block;

class Progress extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'md/productpdf/progress.phtml';
    protected $_coreRegistry;
    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Framework\Registry $coreRegistry, array $data = [])
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }
    
    public function getRegistryUrl()
    {
        $registry = null;
        if ($this->_coreRegistry->registry('pdf_generate_url')) {
            $registry = $this->_coreRegistry->registry('pdf_generate_url');
        }
        return $registry;
    }
}
