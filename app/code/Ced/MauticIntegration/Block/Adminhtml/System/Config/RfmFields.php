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

namespace Ced\MauticIntegration\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;

class RfmFields extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $value = $this->_scopeConfig->getValue('mautic_integration/mautic_rfm_settings/rfm_fields');
        $data = json_decode($value, true);
        $html = "<tr id='row_mautic_integration_mautic_rfm_settings_rfm_fields'>
                <td class='value' style='padding: 0; width: 100%;''>
               <table class='data-grid rfmgrid-wrap'>
                   <thead>
                        <tr><th class='data-grid-th'><span>" . __('Rating') . "</span></th>
                            <th class='data-grid-th'><span>" . __('Recency')."</span><p><span>
                            " . __('(days since last order)')."</span></p></th>
                            <th class='data-grid-th'><span>" . __('Frequency')."</span><p><span>
                            " . __('(total orders placed)')."</span></p></th>
                            <th class='data-grid-th'><span>" . __('Monetary')."</span><p><span>
                            " . __('(total money spent)')."</span></p></th>
                            </tr>
                   </thead>
                   <tbody>
                       <tr class='data-row'>
                           <td style='vertical-align: middle;text-align: center;padding:2rem;'><b>5</b></td>
                           <td style='padding: 2rem;'>
                            <div class='input-wrap'>
                            <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                            ". __('Less Than:')."</span>
                            <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' type='number' 
                            value='" . $data['rfm_at_5']['recency'] . "'
                             name='groups[mautic_rfm_settings][fields][rfm_fields][value][rfm_at_5][recency]'/>
                           </div>
                           </td>
                           <td style='padding: 2rem;'>
                           <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           " . __('More Than:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' type='number' 
                           value='" . $data['rfm_at_5']['frequency'] . "'  
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][rfm_at_5][frequency]'/></div>
                           </td>
                           <td style='padding: 2rem;'>
                           <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           " . __('More Than:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['rfm_at_5']['monetary'] . "' 
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][rfm_at_5][monetary]'/></div>
                           </td>
                       </tr>
                       
                       <tr class='data-row _odd-row'>
                           <td style='vertical-align: middle;text-align: center;padding: 2rem;'><b>4</b></td>
                           <td style='padding: 2rem;'>
                           <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('From:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['from_rfm_4']['recency'] . "' 
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][from_rfm_4][recency]'/>
                           </div>
                            <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           To:</span>
                            <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                            type='number' value='" . $data['to_rfm_4']['recency'] . "' 
                            name='groups[mautic_rfm_settings][fields][rfm_fields][value][to_rfm_4][recency]'/></div>
                           </td>
                           <td style='padding: 2rem;'>
                           <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('From:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['from_rfm_4']['frequency'] . "' 
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][from_rfm_4][frequency]'/>
                           </div>
                           <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           To:</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['to_rfm_4']['frequency'] . "' 
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][to_rfm_4][frequency]'/></div>
                           </td>
                           <td style='padding: 2rem;'>
                           <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('From:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['from_rfm_4']['monetary'] . "' 
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][from_rfm_4][monetary]'/></div>
                           
                           <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           To:</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['to_rfm_4']['monetary'] . "' 
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][to_rfm_4][monetary]'/></div>
                           </td>
                        </tr>
                       
                       <tr class='data-row'>
                            <td style='vertical-align: middle;text-align: center;padding: 1rem;'><b>3</b></td>
                           <td style='padding: 2rem;'>
                           <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('From:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['from_rfm_3']['recency'] . "' 
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][from_rfm_3][recency]'/>
                           </div>
                           <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('To:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['to_rfm_3']['recency'] . "' 
                            name='groups[mautic_rfm_settings][fields][rfm_fields][value][to_rfm_3][recency]'/></div>
                            </td>
                           <td style='padding: 2rem;'>
                           <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('From:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['from_rfm_3']['frequency'] . "' 
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][from_rfm_3][frequency]'/>
                           </div>
                            <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('To:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['to_rfm_3']['frequency'] . "' 
                            name='groups[mautic_rfm_settings][fields][rfm_fields][value][to_rfm_3][frequency]'/></div>
                            </td>
                           <td style='padding: 2rem;'>
                           <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('From:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['from_rfm_3']['monetary'] . "' 
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][from_rfm_3][monetary]'/>
                           </div>
                            <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('To:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['to_rfm_3']['monetary'] . "' 
                            name='groups[mautic_rfm_settings][fields][rfm_fields][value][to_rfm_3][monetary]'/></div>
                            </td>
                        </tr>
                       
                       <tr class='data-row _odd-row'>
                           <td style='vertical-align: middle;text-align: center;padding: 1rem;'><b>2</b></td>
                           <td style='padding: 2rem;'><div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('From:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['from_rfm_2']['recency'] . "' 
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][from_rfm_2][recency]'/></div>
                                <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('To:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['to_rfm_2']['recency'] . "' 
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][to_rfm_2][recency]'/></div></td>
                           <td style='padding: 2rem;'><div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('From:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['from_rfm_2']['frequency'] . "' 
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][from_rfm_2][frequency]'/></div>
                                <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('To:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['to_rfm_2']['frequency'] . "' 
                         name='groups[mautic_rfm_settings][fields][rfm_fields][value][to_rfm_2][frequency]'/></div></td>
                           <td style='padding: 2rem;'><div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('From:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['from_rfm_2']['monetary'] . "' 
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][from_rfm_2][monetary]'/></div>
                                <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           ".__('To:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['to_rfm_2']['monetary'] . "' 
                          name='groups[mautic_rfm_settings][fields][rfm_fields][value][to_rfm_2][monetary]'/></div></td>
                            </tr>
                           
                           <tr>
                           <td style='vertical-align: middle;text-align: center;padding: 2rem;'><b>1</b></td>
                           <td style='padding: 2rem;'>
                           <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           " . __('More Than:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['rfm_at_1']['recency'] . "' 
                           name='groups[mautic_rfm_settings][fields][rfm_fields][value][rfm_at_1][recency]'/></div></td>
                           <td style='padding: 2rem;'>
                           <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           " . __('Less Than:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['rfm_at_1']['frequency'] . "' 
                         name='groups[mautic_rfm_settings][fields][rfm_fields][value][rfm_at_1][frequency]'/></div></td>
                           <td style='padding: 2rem;'>
                           <div class='input-wrap'>
                           <span class='label' style='font-size:12px;font-weight: normal;float: left;min-width: 70px;'>
                           " . __('Less Than:')."</span>
                           <input style='width: calc(100% - 80px);margin-left: 5px;margin-bottom:10px;' 
                           type='number' value='" . $data['rfm_at_1']['monetary'] . "' 
                         name='groups[mautic_rfm_settings][fields][rfm_fields][value][rfm_at_1][monetary]'/></div></td>
                       </tr>
                   </tbody>
               </table>
               </td></tr>";
        return $html;
    }
}
