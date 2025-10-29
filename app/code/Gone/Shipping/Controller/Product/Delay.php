<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Shipping\Controller\Product;

use Gone\Shipping\Helper\Date;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class Delay implements \Magento\Framework\App\Action\HttpPostActionInterface
{

    protected RequestInterface $_request;
    protected JsonFactory $_resultJsonFactory;
    protected ProductRepositoryInterface $_productRepository;
    protected Date $_dateHelper;

    public function __construct(
        RequestInterface $request,
        JsonFactory $resultJsonFactory,
        ProductRepositoryInterface $productRepository,
        Date $dateHelper
    ) {
        $this->_request = $request;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_productRepository = $productRepository;
        $this->_dateHelper = $dateHelper;
    }

    public function execute()
    {
        $result = $this->_resultJsonFactory->create();
        $delayString = "";

        $productId = $this->_request->getParam("productId");
        try {
            $product = $this->_productRepository->getById($productId);
            if ($product->getId()) {
                $delayString = $this->_dateHelper->getProductShippingInfo($product);
            }
        } catch (NoSuchEntityException $e) {
            $delayString = "";
        }

        $result->setData(['output' => $delayString]);
        return $result;
    }
}
