<?php

namespace Gone\AdminPaymentMethod\Plugin;

/**
 * Class PreSelect
 *
 * @package Gone\AdminPaymentMethod\Plugin
 */

class PreSelect
{
    /**
     * @var \Gone\AdminPaymentMethod\Model\AdminPaymentMethod
     */
    private $model;

    /**
     * PreSelect constructor.
     * @param \Gone\AdminPaymentMethod\Model\AdminPaymentMethod $model
     */
    public function __construct(\Gone\AdminPaymentMethod\Model\AdminPaymentMethod $model)
    {
        $this->model = $model;
    }

    /**
     * @param \Magento\Sales\Block\Adminhtml\Order\Create\Billing\Method\Form $block
     * @param $result
     * @return bool|string
     */
    public function afterGetSelectedMethodCode(
        \Magento\Sales\Block\Adminhtml\Order\Create\Billing\Method\Form $block,
        $result
    ) {
        if ($result && $result != 'free') {
            return $result;
        }

        $data = $this->model->getDataPreSelect();
        if ($data) {
            $result = \Gone\AdminPaymentMethod\Model\AdminPaymentMethod::CODE;
            return $result;
        }
        return false;
    }
}
