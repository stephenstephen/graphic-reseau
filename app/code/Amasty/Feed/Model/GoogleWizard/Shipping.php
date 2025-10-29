<?php

namespace Amasty\Feed\Model\GoogleWizard;

/**
 * Class Shipping
 */
class Shipping extends Element
{
    protected $type = 'attribute';

    protected $value = 'shipping';

    protected $template = '<g:shipping>
    <g:country>::country</g:country>
    <g:price>0 ::currency</g:price>
</g:shipping>' . PHP_EOL;

    protected function getEvaluateData()
    {
        $data = parent::getEvaluateData();
        $data['::country'] = $this->direcotryData->getDefaultCountry();
        $data['::currency'] = $this->getFeed()->getFormatPriceCurrency();

        return $data;
    }
}
