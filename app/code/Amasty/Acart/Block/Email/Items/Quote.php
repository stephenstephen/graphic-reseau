<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Abandoned Cart Email Base for Magento 2
 */

namespace Amasty\Acart\Block\Email\Items;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Quote\Api\CartRepositoryInterface;

class Quote extends Template
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    public function __construct(
        Template\Context $context,
        CartRepositoryInterface $cartRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->cartRepository = $cartRepository;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->getQuote() ? $this->getQuote()->getAllVisibleItems() : [];
    }

    public function getQuote()
    {
        try {
            return $this->cartRepository->get((int)$this->getQuoteId());
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }
}
