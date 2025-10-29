<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_MauticIntegration
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license     http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\MauticIntegration\Controller\Config;

use Magento\Framework\App\Action\Context;

class ExportProperties extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;

    /**
     * @var \Ced\MauticIntegration\Helper\ConnectionManager
     */
    public $connectionManager;

    public $exportPropertiesAndSegments;

    public $mauticContactFields = [];

    public $mauticSegments = [];

    /**
     * ExportProperties constructor.
     * @param Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager,
        \Ced\MauticIntegration\Helper\ExportPropertiesAndSegments $exportPropertiesAndSegments
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->connectionManager = $connectionManager;
        $this->exportPropertiesAndSegments = $exportPropertiesAndSegments;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $message = [];
        $response = $this->resultJsonFactory->create();
        $getListResponse = $this->connectionManager->getListOfFields();
        $authenticationResponse = json_decode($getListResponse['response'], true);
        if ($authenticationResponse != null) {
            if (isset($authenticationResponse['errors'])) {
                foreach ($authenticationResponse['errors'] as $error) {
                    if (isset($error['message'])) {
                        $message = ['errors' => $error['message']];
                        break;
                    } else {
                        $message = ['errors' => 'Authorization denied, invalid credentials.'];
                        break;
                    }
                }
                $this->connectionManager->setMauticConfig('connection_established', 0);
                $this->connectionManager->cleanCache();
                $response->setData($message);
                return $response;
            } else {
                $this->updateMauticPropertiesInDatabase($authenticationResponse);
            }
        } elseif (!$authenticationResponse) {
            $this->connectionManager->setMauticConfig('connection_established', 0);
            $this->connectionManager->cleanCache();
            $message = ['errors' => 'Authorization denied, invalid credentials.'];
            $response->setData($message);
            return $response;
        }
        $param = $this->getRequest()->getParam('field');
        if ($param == 'properties') {
            $propertiesResponse = $this->exportPropertiesAndSegments->createProperties();
            if (isset($propertiesResponse['errors'])) {
                foreach ($propertiesResponse['errors'] as $error) {
                    if (in_array($error['code'], [400, 401])) {
                        $message = ['errors' => 'Authorization denied, invalid credentials.'];
                        break;
                    } elseif (isset($error['message'])) {
                        $message = ['errors' => $error['message']];
                        break;
                    }
                }
            } else {
                $message = ['0' => 'Properties has been created.',
                    '1' => 'Segment creation is in process, please do not close window'];
            }
        } elseif ($param == 'segments') {
            $segmentResponse = $this->exportPropertiesAndSegments->createSegments();
            if (isset($segmentResponse['errors'])) {
                foreach ($segmentResponse['errors'] as $error) {
                    if (in_array($error['code'], [400, 401])) {
                        $message = ['errors' => 'Authorization denied, invalid credentials.'];
                        break;
                    } elseif (isset($error['message'])) {
                        $message = ['errors' => $error['message']];
                        break;
                    }
                }
            } else {
                $message = ['0' => 'Segments Created Successfully',
                    '1' => 'You can now close window.'];
            }
        }
        $response->setData($message);
        return $response;
    }

    /**
     * @param $mauticFields
     */
    public function updateMauticPropertiesInDatabase($mauticFields)
    {
        if (isset($mauticFields['total']) && $mauticFields['total'] > 0) {
            $fields = $mauticFields['fields'];
            foreach ($fields as $key => $field) {
                if (strpos($field['alias'], 'ced_') === 0) {
                    $this->mauticContactFields[$field['alias']] = $field;
                }
            }
        }
        $type = \Ced\MauticIntegration\Model\CedMautic::TYPE_PROPERTY;
        if (isset($this->exportPropertiesAndSegments->requiredProperties[$type])) {
            foreach ($this->exportPropertiesAndSegments->requiredProperties[$type] as $property) {
                if (isset($this->mauticContactFields[$property->getCode()]) &&
                    $property->getMauticId() != $this->mauticContactFields[$property->getCode()]['id']) {
                    $this->exportPropertiesAndSegments->saveMauticId(
                        $this->mauticContactFields[$property->getCode()],
                        $type
                    );
                } elseif (!isset($this->mauticContactFields[$property->getCode()]) &&
                    $property->getMauticId() != 0) {
                    $data['alias'] = $property->getCode();
                    $data['id'] = 0;
                    $this->exportPropertiesAndSegments->saveMauticId($data, $type);
                }
            }
        }
        $getSegmentListResponse = $this->connectionManager->getListOfSegments();
        if ($getSegmentListResponse['status_code'] == 200) {
            $segmentResponse = json_decode($getSegmentListResponse['response'], true);
            if ($segmentResponse != null) {
                if (!isset($segmentResponse['errors'])) {
                    $this->updateMauticSegmentsInDatabase($segmentResponse);
                }
            }
        }
    }

    /**
     * @param $mauticSegments
     */
    public function updateMauticSegmentsInDatabase($mauticSegments)
    {
        if (isset($mauticSegments['total']) && $mauticSegments['total'] > 0) {
            $segments = $mauticSegments['lists'];
            foreach ($segments as $key => $segment) {
                if (strpos($segment['alias'], 'ced-') === 0) {
                    $this->mauticSegments[$segment['alias']] = $segment;
                }
            }
        }
        $type = \Ced\MauticIntegration\Model\CedMautic::TYPE_SEGMENT;
        if (isset($this->exportPropertiesAndSegments->requiredProperties[$type])) {
            foreach ($this->exportPropertiesAndSegments->requiredProperties[$type] as $property) {
                if (isset($this->mauticSegments[$property->getCode()]) &&
                    $property->getMauticId() != $this->mauticSegments[$property->getCode()]['id']) {
                    $this->exportPropertiesAndSegments->saveMauticId(
                        $this->mauticSegments[$property->getCode()],
                        $type
                    );
                } elseif (!isset($this->mauticSegments[$property->getCode()]) &&
                    $property->getMauticId() != 0) {
                    $data['alias'] = $property->getCode();
                    $data['id'] = 0;
                    $this->exportPropertiesAndSegments->saveMauticId($data, $type);
                }
            }
        }
    }
}
