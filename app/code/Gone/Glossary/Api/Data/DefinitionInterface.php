<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Glossary\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface DefinitionInterface extends ExtensibleDataInterface
{

    public const TEXT = 'text';
    public const CASE_SENSIBLE = 'case_sensible';
    public const DESCRIPTION = 'description';
    public const STATUS = 'status';
    public const DEFINITION_ID = 'definition_id';

    /**
     * Get definition_id
     * @return string|null
     */
    public function getDefinitionId();

    /**
     * Set definition_id
     * @param string $definitionId
     * @return \Gone\Glossary\Api\Data\DefinitionInterface
     */
    public function setDefinitionId($definitionId);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Gone\Glossary\Api\Data\DefinitionInterface
     */
    public function setStatus($status);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Gone\Glossary\Api\Data\DefinitionExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Gone\Glossary\Api\Data\DefinitionExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Gone\Glossary\Api\Data\DefinitionExtensionInterface $extensionAttributes
    );

    /**
     * Get text
     * @return string|null
     */
    public function getText();

    /**
     * Set text
     * @param string $text
     * @return \Gone\Glossary\Api\Data\DefinitionInterface
     */
    public function setText($text);

    /**
     * Get case_sensible
     * @return string|null
     */
    public function getCaseSensible();

    /**
     * Set case_sensible
     * @param string $caseSensible
     * @return \Gone\Glossary\Api\Data\DefinitionInterface
     */
    public function setCaseSensible($caseSensible);

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \Gone\Glossary\Api\Data\DefinitionInterface
     */
    public function setDescription($description);
}
