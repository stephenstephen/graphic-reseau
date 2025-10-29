<?php

namespace Magedelight\Productpdf\Model\System\Config\Backend;

class Fonts extends \Magento\Config\Model\Config\Backend\Image
{
    protected function _getAllowedExtensions()
    {
        return ['ttf'];
    }
    public function beforeSave()
    {
        parent::beforeSave();
        $this->setValue('');
        return $this;
    }
}
