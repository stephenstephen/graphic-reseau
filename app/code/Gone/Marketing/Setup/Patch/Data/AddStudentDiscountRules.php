<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Marketing\Setup\Patch\Data;

use Gone\Customer\Helper\Customer;
use Magento\CatalogRule\Api\CatalogRuleRepositoryInterface;
use Magento\CatalogRule\Api\Data\ConditionInterface;
use Magento\CatalogRule\Api\Data\ConditionInterfaceFactory;
use Magento\CatalogRule\Api\Data\RuleInterface;
use Magento\CatalogRule\Api\Data\RuleInterfaceFactory;
use Magento\CatalogRule\Model\Rule\Condition\Combine;
use Magento\CatalogRule\Model\Rule\Condition\Product;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\SalesRule\Model\Rule;
use Psr\Log\LoggerInterface;

class AddStudentDiscountRules implements DataPatchInterface
{

    protected RuleInterfaceFactory $_dataRuleFactory;
    protected CatalogRuleRepositoryInterface $_ruleRepository;
    protected Customer $_customerHelper;
    protected LoggerInterface $_logger;
    protected ConditionInterfaceFactory $_ruleConditionFactory;
    private ModuleDataSetupInterface $moduleDataSetup;
    private State $_state;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        RuleInterfaceFactory $dataRuleFactory,
        CatalogRuleRepositoryInterface $ruleRepository,
        Customer $customerHelper,
        ConditionInterfaceFactory $ruleConditionFactory,
        LoggerInterface $logger,
        State $state
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->_dataRuleFactory = $dataRuleFactory;
        $this->_ruleRepository = $ruleRepository;
        $this->_logger = $logger;
        $this->_customerHelper = $customerHelper;
        $this->_ruleConditionFactory = $ruleConditionFactory;
        $this->_state = $state;
    }

    public static function getDependencies(): array
    {
        return [
            AddStudentDiscountAttribute::class
        ];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        try {

            $this->_state->setAreaCode(Area::AREA_ADMINHTML);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

        $this->moduleDataSetup->getConnection()->startSetup();

        $studentGroupId = $this->_customerHelper->getGroupIdByName(Customer::STUDENT_GROUP_NAME);

        /** @var ConditionInterface $rootCondition */
        $rootCondition = $this->_ruleConditionFactory->create();
        $rootCondition->setType(Combine::class)
            ->setAggregator('all')
            ->setValue(true);

        /** @var ConditionInterface $condition */
        $condition = $this->_ruleConditionFactory->create();
        $condition->setType(Product::class)
            ->setAttribute('student_discount')
            ->setOperator('==')
            ->setValue(true);

        $rootCondition->setConditions([$condition]);

        /** @var RuleInterface $rule */
        $rule = $this->_dataRuleFactory->create();
        $rule->setName('Student Discount')
            ->setIsActive(true)
            ->setDiscountAmount('5')
            ->setSimpleAction(Rule::BY_PERCENT_ACTION)
            ->setRuleCondition($rootCondition)
            ->setCustomerGroupIds($studentGroupId)
            ->setWebsiteIds([1]);

        $this->_ruleRepository->save($rule);

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
