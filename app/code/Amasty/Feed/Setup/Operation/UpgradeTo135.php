<?php

namespace Amasty\Feed\Setup\Operation;

/**
 * Class UpgradeTo135
 */
class UpgradeTo135
{
    /**
     * @var \Amasty\Base\Setup\SerializedFieldDataConverter
     */
    private $fieldDataConverter;

    public function __construct(\Amasty\Base\Setup\SerializedFieldDataConverter $fieldDataConverter)
    {
        $this->fieldDataConverter = $fieldDataConverter;
    }

    public function execute()
    {
        $this->fieldDataConverter->convertSerializedDataToJson(
            'amasty_feed_entity',
            'entity_id',
            ['conditions_serialized']
        );
    }
}
