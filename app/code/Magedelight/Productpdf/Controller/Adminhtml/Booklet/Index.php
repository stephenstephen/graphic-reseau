<?php

namespace Magedelight\Productpdf\Controller\Adminhtml\Booklet;

class Index extends \Magento\Backend\App\Action
{
    
    public function _initAction()
    {
        $this->_view->loadLayout();
        return $this;
    }
    
    public function execute()
    {
        $this->_initAction()->_setActiveMenu(
            'Magedelight_Productpdf::print_booklet'
        )->_addBreadcrumb(
            __('PDF Catalog Booklet'),
            __('PDF Catalog Booklet')
        );
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('PDF Catalog Booklet'));
        $this->_view->renderLayout();
    }
    
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magedelight_Productpdf::print_booklet');
    }
}
