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

namespace Ced\MauticIntegration\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

class ExportPropertiesAndSegments extends AbstractHelper
{
    public $requiredProperties;

    public $properties;

    public $connectionManager;

    /**
     * @var \Ced\MauticIntegration\Model\ResourceModel\CedMautic\CollectionFactory
     */
    public $cedmauticCollectionFactory;

    /**
     * @var \Ced\MauticIntegration\Model\CedMauticFactory
     */
    public $cedMauticFactory;

    /**
     * ExportPropertiesAndSegments constructor.
     * @param Context $context
     * @param Properties $properties
     * @param ConnectionManager $connectionManager
     * @param \Ced\MauticIntegration\Model\ResourceModel\CedMautic\CollectionFactory $cedmauticCollectionFactory
     * @param \Ced\MauticIntegration\Model\CedMauticFactory $cedMauticFactory
     */
    public function __construct(
        Context $context,
        Properties $properties,
        ConnectionManager $connectionManager,
        \Ced\MauticIntegration\Model\ResourceModel\CedMautic\CollectionFactory $cedmauticCollectionFactory,
        \Ced\MauticIntegration\Model\CedMauticFactory $cedMauticFactory
    ) {
        $this->properties = $properties;
        $this->connectionManager = $connectionManager;
        $this->cedmauticCollectionFactory = $cedmauticCollectionFactory;
        $this->cedMauticFactory = $cedMauticFactory;
        $this->getPropertiesFromDb();
        parent::__construct($context);
    }

    /**
     * Create the contact property
     */
    public function createProperties()
    {
        $this->getPropertiesFromDb();
        $endpoint = '/api/fields/contact/new';
        $method = 'POST';
        $groups = $this->properties->allGroups();
        foreach ($groups as $group) {
            $properties = $this->properties->allProperties($group['name']);

            foreach ($properties as $property) {
                if ($this->isRequiredProperty(
                    $property['alias'],
                    \Ced\MauticIntegration\Model\CedMautic::TYPE_PROPERTY
                )) {
                    $contactResponse = $this->connectionManager->createRequest($method, $endpoint, $property);
                    if ($contactResponse['status_code'] == 201) {
                        $response = json_decode($contactResponse['response'], true);
                        if ($response != null && !isset($response['errors']) && isset($response['field'])) {
                            $this->saveMauticId(
                                $response['field'],
                                \Ced\MauticIntegration\Model\CedMautic::TYPE_PROPERTY
                            );
                        }
                    } elseif ($contactResponse['status_code'] == 200) {
                        $response = json_decode($contactResponse['response'], true);
                        if (isset($response['errors'])) {
                            return $response;
                        }
                    }
                }
            }
        }
    }

    /**
     * Create segment
     */
    public function createSegments()
    {
        $this->getPropertiesFromDb();
        $endpoint = '/api/segments/new';
        $method = 'POST';
        $segments = $this->properties->getMauticSegments();
        foreach ($segments as $segment) {
            if ($this->isRequiredProperty(
                $segment['alias'],
                \Ced\MauticIntegration\Model\CedMautic::TYPE_SEGMENT
            )) {
                $segmentResponse = $this->connectionManager->createRequest($method, $endpoint, $segment);
                if ($segmentResponse['status_code'] == 201) {
                    $response = json_decode($segmentResponse['response'], true);
                    if ($response != null && !isset($response['errors']) && isset($response['list'])) {
                        $this->saveMauticId($response['list'], \Ced\MauticIntegration\Model\CedMautic::TYPE_SEGMENT);
                    }
                } elseif ($segmentResponse['status_code'] == 200) {
                    $response = json_decode($segmentResponse['response'], true);
                    if (isset($response['errors'])) {
                        return $response;
                    }
                }
            }
        }

        $this->connectionManager->setMauticConfig('connection_established', 1);
        $this->connectionManager->connectionEstablished = 1;
        $this->connectionManager->cleanCache();
    }

    /**
     * @param $propertyCode
     */
    public function createSingleProperty($propertyCode)
    {
        $endpoint = '/api/fields/contact/new';
        $method = 'POST';
        $groups = $this->properties->allGroups();
        foreach ($groups as $group) {
            $properties = $this->properties->allProperties($group['name']);
            if (isset($properties[$propertyCode])) {
                $property = $properties[$propertyCode];
                $contactResponse = $this->connectionManager->createRequest($method, $endpoint, $property);
                $response = json_decode($contactResponse['response'], true);
                if ($contactResponse['status_code'] == 201) {
                    if ($response != null && !isset($response['errors']) && isset($response['field'])) {
                        $this->saveMauticId(
                            $response['field'],
                            \Ced\MauticIntegration\Model\CedMautic::TYPE_PROPERTY
                        );
                    }
                }
                break;
            }
        }
        return $response;
    }

    /**
     * @param $segmentCode
     */
    public function createSingleSegment($segmentCode)
    {
        $endpoint = '/api/segments/new';
        $method = 'POST';
        $segments = $this->properties->getMauticSegments();
        if (isset($segments[$segmentCode])) {
            $segment = $segments[$segmentCode];
            $segmentResponse = $this->connectionManager->createRequest($method, $endpoint, $segment);
            $response = json_decode($segmentResponse['response'], true);
            if ($segmentResponse['status_code'] == 201) {
                if ($response != null && !isset($response['errors']) && isset($response['list'])) {
                    $this->saveMauticId($response['list'], \Ced\MauticIntegration\Model\CedMautic::TYPE_SEGMENT);
                }
            }
        }
        return $response;
    }

    public function getPropertiesFromDb()
    {
        $allProperties = $this->cedmauticCollectionFactory->create();
        foreach ($allProperties as $property) {
            $this->requiredProperties[$property->getEntityType()][$property->getCode()] = $property;
        }
    }

    /**
     * @param $propertyCode
     * @param $entityType
     * @return bool
     */
    public function isRequiredProperty($propertyCode, $entityType)
    {
        if (isset($this->requiredProperties[$entityType][$propertyCode])) {
            $property = $this->requiredProperties[$entityType][$propertyCode];
            if ($property->getMauticId() == 0 && $property->getIsRequired()) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param $propertyCode
     * @param $entityType
     * @return bool
     */
    public function canSetProperty($propertyCode, $entityType)
    {
        if (isset($this->requiredProperties[$entityType][$propertyCode])) {
            $property = $this->requiredProperties[$entityType][$propertyCode];
            if ($property->getMauticId() != 0 && $property->getIsRequired()) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function saveMauticId($response, $entityType)
    {
        if (isset($this->requiredProperties[$entityType][$response['alias']])) {
            $id = $this->requiredProperties[$entityType][$response['alias']]->getId();
            $cedMautic = $this->cedMauticFactory->create()->load($id);
            $cedMautic->setData('is_required', true);
            $cedMautic->setData('mautic_id', $response['id']);
            try {
                $cedMautic->save();
            } catch (\Exception $e) {
                $this->_logger->critical($e->getMessage());
            }
        }
    }
}
