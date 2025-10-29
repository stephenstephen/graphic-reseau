<?php

namespace Gone\Marketing\Plugin\Controller\Frontend\Block;

use Gone\Marketing\Ui\Column\Cms\Block\CmsCustomerSegments;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Serialize\Serializer\Json;

class Render
{
    protected Json $_json;
    protected HttpContext $_httpContext;

    public function __construct(
        Json $json,
        HttpContext $httpContext
    )
    {
        $this->_json = $json;
        $this->_httpContext = $httpContext;
    }

    public function beforeGetContent(
        \Magento\Cms\Model\Block $subject
    )
    {
        //block linked to segment?
        if ($subject->getSegmentsAssignation()) {
            $blockSegment = $this->_json->unserialize($subject->getSegmentsAssignation());

            //is segment set to ALL CUSTOMERS ?
            if (!in_array(CmsCustomerSegments::DEFAULT_ALL_CUSTOMER_SEGMENT['value'], $blockSegment)) {

                $segmentKey = \Aheadworks\CustomerSegmentation\Model\Customer\Context::CONTEXT_AW_CS_SEGMENT_IDS;
                $httpContextData = $this->_httpContext->getData();

                //is logged customer belongs to segments ?
                if (array_key_exists($segmentKey, $httpContextData)) {

                    $customerSegment = $httpContextData[$segmentKey];

                    //block and customer don't match same segments + block doesn't belong to "ALL segments" > emptyContent
                    if (!empty(array_diff($blockSegment, $customerSegment))
                    ) {
                        $subject->setContent('');
                    }
                } else {
                    $subject->setContent('');
                }
            }
        }

    }
}
