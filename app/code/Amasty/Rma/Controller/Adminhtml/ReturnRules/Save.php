<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Controller\Adminhtml\ReturnRules;

use Amasty\Rma\Api\Data\ReturnRulesInterface;
use Amasty\Rma\Api\ReturnRulesRepositoryInterface;
use Amasty\Rma\Controller\Adminhtml\AbstractReturnRules;
use Amasty\Rma\Controller\Adminhtml\RegistryConstants;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;

class Save extends AbstractReturnRules
{
    /**
     * @var ReturnRulesRepositoryInterface
     */
    private $rulesRepository;

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var DataObject
     */
    private $dataObject;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Action\Context $context,
        ReturnRulesRepositoryInterface $rulesRepository,
        Registry $coreRegistry,
        DataPersistorInterface $dataPersistor,
        DataObject $dataObject,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->rulesRepository = $rulesRepository;
        $this->coreRegistry = $coreRegistry;
        $this->dataPersistor = $dataPersistor;
        $this->dataObject = $dataObject;
        $this->logger = $logger;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            try {
                $id = (int)$this->getRequest()->getParam(RegistryConstants::RULE_ID);

                if ($id) {
                    $model = $this->rulesRepository->getById($id);
                } else {
                    $model = $this->rulesRepository->getEmptyRuleModel();
                }

                if (!$this->validateResult($data, $model)) {
                    return;
                }
                $this->saveRuleModel($model, $data);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('amrma/*/edit', [RegistryConstants::RULE_ID => $model->getId()]);

                    return;
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                if (!empty($id)) {
                    $this->_redirect('amrma/*/edit', [RegistryConstants::RULE_ID => $id]);
                } else {
                    $this->_redirect('amrma/*/create');
                }
                $this->dataPersistor->set('amrma_returnrule', $data);

                return;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while saving the rule data. Please review the error log.')
                );
                $this->logError($e, $data, $id);

                return;
            }
        }
        $this->_redirect('amrma/*/');
    }

    /**
     * @param ReturnRulesInterface $model
     * @param array $data
     */
    public function saveRuleModel($model, &$data)
    {
        if (isset($data['rule'])) {
            if (isset($data['rule']['conditions'])) {
                $data['conditions'] = $data['rule']['conditions'];
            }
            unset($data['rule']);
        }
        $model->loadPost($data);
        $model->setResolutions($this->getResolutions($data));
        $model->setWebsites($this->getWebsites($data));
        $model->setCustomerGroups($this->getCustomerGroups($data));

        $this->dataPersistor->set('amrma_returnrule', $data);
        $this->rulesRepository->save($model);

        $this->messageManager->addSuccessMessage(__('The rule is saved.'));
        $this->dataPersistor->clear('amrma_returnrule');
    }

    private function getResolutions($data)
    {
        $resolutionsArray = [];

        foreach ($data as $key => $value) {
            if (stripos($key, 'resolution_') !== false) {
                $ruleResolution = $this->rulesRepository->getEmptyRuleResolutionModel();
                $id = str_replace('resolution_', '', $key);

                if (!empty($data['use_default'][$key])) {
                    $value = null;
                }
                $ruleResolution->setValue($value)->setResolutionId($id);
                $resolutionsArray[] = $ruleResolution;
            }
        }

        return $resolutionsArray;
    }

    private function getWebsites($data)
    {
        $websites = [];

        if (!empty($data['websites'])) {
            foreach ($data['websites'] as $websiteId) {
                $ruleWebsite = $this->rulesRepository->getEmptyRuleWebsiteModel();
                $ruleWebsite->setWebsiteId($websiteId);
                $websites[] = $ruleWebsite;
            }
        }

        return $websites;
    }

    private function getCustomerGroups($data)
    {
        $customerGroups = [];

        if (isset($data['customer_groups']) && !empty($data['customer_groups'])) {
            foreach ($data['customer_groups'] as $groupId) {
                $ruleCustomerGroup = $this->rulesRepository->getEmptyRuleCustomerGroupModel();
                $ruleCustomerGroup->setCustomerGroupId($groupId);
                $customerGroups[] = $ruleCustomerGroup;
            }
        }

        return $customerGroups;
    }

    /**
     * @param array $data
     * @param \Amasty\Rma\Api\Data\ReturnRulesInterface $model
     *
     * @return bool
     */
    private function validateResult($data, $model)
    {
        $validateResult = $model->validateData($this->dataObject->addData($data));

        if ($validateResult !== true) {
            foreach ($validateResult as $errorMessage) {
                $this->messageManager->addErrorMessage($errorMessage);
            }
            $this->saveFormDataAndRedirect($data, $model->getId());

            return false;
        }

        return true;
    }

    /**
     * @param \Exception $e
     * @param array $data
     * @param int $id
     */
    private function logError($e, $data, $id)
    {
        $this->logger->critical($e);
        $this->saveFormDataAndRedirect($data, $id);
    }

    /**
     * @param array $data
     * @param int $id
     */
    private function saveFormDataAndRedirect($data, $id)
    {
        $this->dataPersistor->set('amrma_returnrule', $data);
        $this->_redirect('amrma/*/edit', [RegistryConstants::RULE_ID => $id]);
    }
}
