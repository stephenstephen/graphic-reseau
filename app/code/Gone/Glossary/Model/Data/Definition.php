<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Glossary\Model\Data;

use Gone\Glossary\Api\Data\DefinitionExtensionInterface;
use Gone\Glossary\Api\Data\DefinitionInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

class Definition extends AbstractExtensibleObject implements DefinitionInterface
{

    /**
     * Get definition_id
     * @return string|null
     */
    public function getDefinitionId()
    {
        return $this->_get(self::DEFINITION_ID);
    }

    /**
     * Set definition_id
     * @param string $definitionId
     * @return DefinitionInterface
     */
    public function setDefinitionId($definitionId)
    {
        return $this->setData(self::DEFINITION_ID, $definitionId);
    }

    /**
     * Get status
     * @return string|null
     */
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }

    /**
     * Set status
     * @param string $status
     * @return DefinitionInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return DefinitionExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param DefinitionExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        DefinitionExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get text
     * @return string|null
     */
    public function getText()
    {
        return $this->_get(self::TEXT);
    }

    /**
     * Set text
     * @param string $text
     * @return DefinitionInterface
     */
    public function setText($text)
    {
        return $this->setData(self::TEXT, $text);
    }

    /**
     * Get case_sensible
     * @return string|null
     */
    public function getCaseSensible()
    {
        return $this->_get(self::CASE_SENSIBLE);
    }

    /**
     * Set case_sensible
     * @param string $caseSensible
     * @return DefinitionInterface
     */
    public function setCaseSensible($caseSensible)
    {
        return $this->setData(self::CASE_SENSIBLE, $caseSensible);
    }

    /**
     * Get description
     * @return string|null
     */
    public function getDescription()
    {
        return $this->_get(self::DESCRIPTION);
    }

    /**
     * Set description
     * @param string $description
     * @return DefinitionInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }
}
