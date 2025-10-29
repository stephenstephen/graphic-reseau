<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Subligraphy\Model;

use Gone\Subligraphy\Api\Data\CertificateInterface;
use Gone\Subligraphy\Api\Data\CertificateInterfaceFactory;
use Gone\Subligraphy\Model\ResourceModel\Certificate\Collection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class Certificate extends AbstractModel
{

    protected $certificateDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'gr_subligraphy_certificate';

    /**
     * @param Context $context
     * @param Registry $registry
     * @param CertificateInterfaceFactory $certificateDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param ResourceModel\Certificate $resource
     * @param Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        CertificateInterfaceFactory $certificateDataFactory,
        DataObjectHelper $dataObjectHelper,
        ResourceModel\Certificate $resource,
        Collection $resourceCollection,
        array $data = []
    ) {
        $this->certificateDataFactory = $certificateDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve certificate model with certificate data
     * @return CertificateInterface
     */
    public function getDataModel()
    {
        $certificateData = $this->getData();

        $certificateDataObject = $this->certificateDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $certificateDataObject,
            $certificateData,
            CertificateInterface::class
        );

        return $certificateDataObject;
    }
}
