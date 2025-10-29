<?php

namespace Amasty\Feed\Setup\Operation;

/**
 * Class UpgradeTo180
 */
class UpgradeTo180
{
    /**
     * @var \Amasty\Feed\Model\ResourceModel\Feed\CollectionFactory
     */
    private $feedCollectionFactory;

    /**
     * @var \Amasty\Feed\Model\ResourceModel\Feed
     */
    private $resourceModelFeed;

    public function __construct(
        \Amasty\Feed\Model\ResourceModel\Feed\CollectionFactory $feedCollectionFactory,
        \Amasty\Feed\Model\ResourceModel\Feed $resourceModelFeed
    ) {
        $this->feedCollectionFactory = $feedCollectionFactory;
        $this->resourceModelFeed = $resourceModelFeed;
    }

    public function execute()
    {
        /** @var \Amasty\Feed\Model\ResourceModel\Feed\Collection $feedCollection */
        $feedCollection = $this->feedCollectionFactory->create();
        $feedCollection->addFieldToFilter('is_template', '1')
            ->addFieldToFilter('name', 'Google')
            ->addFieldToFilter('feed_type', 'xml');

        /** @var \Amasty\Feed\Model\Feed $feed */
        $feed = $feedCollection->getFirstItem();

        if ($feed) {
            $feed->setXmlHeader($feed->getXmlHeader() . '<created_at>{{DATE}}</created_at>');
            $this->resourceModelFeed->save($feed);
        }
    }
}
