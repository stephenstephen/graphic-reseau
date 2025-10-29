<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2017 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Model;

use Colissimo\Shipping\Api\GuestPickupRepositoryInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;

/**
 * Class GuestPickupRepository
 */
class GuestPickupRepository implements GuestPickupRepositoryInterface
{

    /**
     * @var PickupRepository $pickupRepository
     */
    protected $pickupRepository;

    /**
     * @var QuoteIdMaskFactory
     */
    protected $quoteIdMaskFactory;

    /**
     * @param PickupRepository $pickupRepository
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     */
    public function __construct(
        PickupRepository $pickupRepository,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->pickupRepository   = $pickupRepository;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function current($cartId)
    {
        return $this->pickupRepository->current($this->getCartId($cartId));
    }

    /**
     * {@inheritdoc}
     */
    public function save($cartId, $pickupId, $networkCode, $telephone)
    {
        return $this->pickupRepository->save($this->getCartId($cartId), $pickupId, $networkCode, $telephone);
    }

    /**
     * {@inheritdoc}
     */
    public function reset($cartId)
    {
        return $this->pickupRepository->reset($this->getCartId($cartId));
    }

    /**
     * Retrieve cart Id
     *
     * @param int $cartId
     * @return int
     */
    protected function getCartId($cartId)
    {
        /** @var $quoteIdMask QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');

        return $quoteIdMask->getQuoteId();
    }
}
