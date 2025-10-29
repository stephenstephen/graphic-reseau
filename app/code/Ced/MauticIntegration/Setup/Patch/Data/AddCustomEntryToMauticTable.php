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

namespace Ced\MauticIntegration\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Ced\MauticIntegration\Model\ResourceModel\CedMautic\CollectionFactory As CedMauticCollectionFactory;
use Ced\MauticIntegration\Model\ResourceModel\CedMautic As CedMauticResource;
use Ced\MauticIntegration\Helper\Properties;

/**
 * Class AddCustomEntryToMauticTable
 * @package Ced\MauticIntegration\Setup\Patch\Data
 */
class AddCustomEntryToMauticTable implements DataPatchInterface
{
    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */

    /**
     * @var CedMauticCollectionFactory
     */
    public $cedMauticCollectionFactory;

    /**
     * @var CedMauticResource
     */
    public $cedMauticResource;

    /**
     * @var \Ced\MauticIntegration\Helper\Properties
     */
    public $properties;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CedMauticCollectionFactory $cedMauticCollectionFactory
     * @param CedMauticResource $cedMauticResource
     * @param Properties $properties
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CedMauticCollectionFactory $cedMauticCollectionFactory,
        CedMauticResource $cedMauticResource,
        Properties $properties
    ) {

        $this->moduleDataSetup = $moduleDataSetup;
        $this->cedMauticCollectionFactory = $cedMauticCollectionFactory;
        $this->cedMauticResource = $cedMauticResource;
        $this->properties = $properties;
    }

    public static function getDependencies()
    {
        return [];
    }

    /**
     * Rollback all changes, done by this patch
     *
     * @return void
     */
    public function revert()
    {
    }

    /**
     * Get aliases (previous names) for the patch.
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $notRequiredProperties = ['ced_ship_add_line_1', 
                                  'ced_ship_add_line_2', 
                                  'ced_ship_city', 
                                  'ced_ship_state',
                                  'ced_ship_post_code', 
                                  'ced_ship_country', 
                                  'ced_prod_2_img_url', 
                                  'ced_prod_2_name', 
                                  'ced_prod_2_price',
                                  'ced_prod_2_url', 
                                  'ced_prod_3_img_url', 
                                  'ced_prod_3_name', 
                                  'ced_prod_3_price', 
                                  'ced_prod_3_url',
                                  'ced_abncart_prod_html', 
                                  'ced_abncart_prods_count', 
                                  'ced_abncart_total'
                                ];
        
        $groups = $this->properties->allGroups();
        foreach ($groups as $group) {
            $properties = $this->properties->allProperties($group['name']);
            foreach ($properties as $property) {
                $cedMautic = $this->cedMauticCollectionFactory->create()
                ->addFieldToFilter('entity_type', 'properties')
                ->addFieldToFilter('code', $property['alias'])
                ->getFirstItem();
                if (in_array($property['alias'], $notRequiredProperties)) {
                    $cedMautic->setData('entity_type', 'properties');
                    $cedMautic->setData('code', $property['alias']);
                    $cedMautic->setData('name', $property['label']);
                    $cedMautic->setData('is_required', false);
                    $cedMautic->setData('mautic_id', 0);
                } else {
                    $cedMautic->setData('entity_type', 'properties');
                    $cedMautic->setData('code', $property['alias']);
                    $cedMautic->setData('name', $property['label']);
                    $cedMautic->setData('is_required', true);
                    $cedMautic->setData('mautic_id', 0);
                }
                $this->cedMauticResource->save($cedMautic);
            }
        } 
        
        $notRequiredSegments = ['ced-newsletter-subs'];
        $segments = $this->properties->getMauticSegments();
        foreach ($segments as $segment) {
            $cedMautic = $this->cedMauticCollectionFactory->create()
                ->addFieldToFilter('entity_type', 'segments')
                ->addFieldToFilter('code', $segment['alias'])
                ->getFirstItem();
            if (in_array($segment['alias'], $notRequiredSegments)) {
                $cedMautic->setData('entity_type', 'segments');
                $cedMautic->setData('code', $segment['alias']);
                $cedMautic->setData('name', $segment['name']);
                $cedMautic->setData('is_required', false);
                $cedMautic->setData('mautic_id', 0);
            } else {
                $cedMautic->setData('entity_type', 'segments');
                $cedMautic->setData('code', $segment['alias']);
                $cedMautic->setData('name', $segment['name']);
                $cedMautic->setData('is_required', true);
                $cedMautic->setData('mautic_id', 0);
            }
            $this->cedMauticResource->save($cedMautic);
        }
        $this->moduleDataSetup->endSetup();
    }
}
