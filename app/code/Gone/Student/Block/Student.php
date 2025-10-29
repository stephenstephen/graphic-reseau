<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Student\Block;

use Gone\Customer\Helper\Customer;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\View\Element\Template;

class Student extends Template
{

    public const MAX_UPLOAD = 2; // Mo

    protected CurrentCustomer $_currentCustomer;
    protected GroupRepositoryInterface $_groupRepository;

    public function __construct(
        Template\Context $context,
        CurrentCustomer $currentCustomer,
        GroupRepositoryInterface $groupRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_currentCustomer = $currentCustomer;
        $this->_groupRepository = $groupRepository;
    }

    public function getFormAction()
    {
        return $this->getUrl('student/validate/post');
    }

    public function isCustomerStudent()
    {
        $customerGroupId = $this->_currentCustomer->getCustomer()->getGroupId();
        $customerGroupName = $this->_groupRepository->getById($customerGroupId)->getCode();

        if ($customerGroupName == Customer::STUDENT_GROUP_NAME) {
            return true;
        }
        return false;
    }
}
