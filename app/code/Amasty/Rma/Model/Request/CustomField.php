<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


declare(strict_types=1);

namespace Amasty\Rma\Model\Request;

use Amasty\Rma\Api\Data\RequestCustomFieldInterface;

class CustomField implements RequestCustomFieldInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    public function __construct(
        string $key = '',
        string $value = ''
    ) {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @inheritdoc
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $key;
    }

    /**
     * @inheritdoc
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $value;
    }
}
