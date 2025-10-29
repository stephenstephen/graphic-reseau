<?php
/*
 *
 * @copyright Copyright © 2020 410-Gone. All rights reserved.
 * @author    contact@410-gone.fr
 *
 */

namespace Gone\Cloaking\Plugin;

class WysiwygConfigPlugin
{
    public function afterGetConfig(\Magento\Cms\Model\Wysiwyg\Config $subject, \Magento\Framework\DataObject $config)
    {
        $config->addData(
            [
                'settings' => [
                    'link_class_list' => [
                        ['title'=> 'None', 'value'=> ''],
                        ['title'=> 'Cloaking', 'value'=> \Gone\Cloaking\Helper\Replace::LINK_TO_CLOAK_CLASS],
                        /*Custom to add download class in wysiwyg links*/
                        ['title'=> 'Download File', 'value'=> 'enlaps-button btn-download']
                    ]
                ]
            ]
        );

        return $config;
    }
}
