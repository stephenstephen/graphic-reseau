<?php
namespace Netreviews\Avisverifies\Model\Config\Source;

class customSelectActivateRichSnippets implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'default', 'label' => __('No rich-snippets')],
            ['value' => 'schema', 'label' => __('Rich-snippets using microdata format')],
            ['value' => 'jsonld', 'label' => __('Rich-snippets using JSON-LD format')]
        ];
    }
}
