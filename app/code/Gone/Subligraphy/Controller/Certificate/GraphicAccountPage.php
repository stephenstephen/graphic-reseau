<?php

namespace Gone\Subligraphy\Controller\Certificate;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use Gone\Subligraphy\Helper\SubligraphyConfig;

class GraphicAccountPage implements HttpGetActionInterface
{
    protected SubligraphyConfig $_subligraphyConfig;

    public function __construct(
        PageFactory $pageFactory,
        SubligraphyConfig $subligraphyConfig,
        RedirectFactory $redirectFactory
    ) {
        $this->pageFactory = $pageFactory;
        $this->redirectFactory = $redirectFactory;
        $this->_subligraphyConfig = $subligraphyConfig;
    }

    public function execute()
    {

        if (!$this->_subligraphyConfig->isSubligraphAuth()) {
            return $this->redirectFactory->create()->setPath('customer/account');
        }
        return $this->pageFactory->create();
    }
}
