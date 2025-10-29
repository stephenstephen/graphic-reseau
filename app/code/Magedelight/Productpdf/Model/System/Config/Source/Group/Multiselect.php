<?php

namespace Magedelight\Productpdf\Model\System\Config\Source\Group;

use Magento\Customer\Api\GroupManagementInterface;

class Multiselect extends \Magento\Customer\Model\Config\Source\Group\Multiselect
{
    /**
     * Customer groups options array
     *
     * @var null|array
     */
    protected $_options;

    /**
     * @var GroupManagementInterface
     */
    protected $_groupManagement;

    /**
     * @var \Magento\Framework\Convert\DataObject
     */
    protected $_converter;

    /**
     * @param GroupManagementInterface $groupManagement
     * @param \Magento\Framework\Convert\DataObject $converter
     */
    public function __construct(
        GroupManagementInterface $groupManagement,
        \Magento\Framework\Convert\DataObject $converter,
        \Magento\Customer\Model\ResourceModel\Group\Collection $mdcustomergroups
    ) {
        $this->_groupManagement = $groupManagement;
        $this->_converter = $converter;
        $this->_allgroupcollection = $mdcustomergroups;
    }

    /**
     * Retrieve customer groups as array
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = $this->_allgroupcollection->toOptionArray();
        }
        return $this->_options;
    }
}
