<?php

/**
 * TechDivision\Import\Product\Media\Ee\Observers\EeMediaGalleryUpdateObserver
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media-ee
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Media\Ee\Observers;

use TechDivision\Import\Product\Media\Ee\Utils\MemberNames;

/**
 * Observer that provides extended mapping functionality to map a SKU to a row ID (EE Feature).
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media-ee
 * @link      http://www.techdivision.com
 */
class EeMediaGalleryUpdateObserver extends EeMediaGalleryObserver
{

    /**
     * Merge's and return's the entity with the passed attributes and set's the
     * passed status.
     *
     * @param array       $entity        The entity to merge the attributes into
     * @param array       $attr          The attributes to be merged
     * @param string|null $changeSetName The change set name to use
     *
     * @return array The merged entity
     */
    protected function mergeEntity(array $entity, array $attr, $changeSetName = null)
    {

        // temporary persist the parent value ID
        $this->setParentValueId($entity[MemberNames::VALUE_ID]);

        // merge and return the entity
        return parent::mergeEntity($entity, $attr, $changeSetName);
    }

    /**
     * Initialize the product media gallery with the passed attributes and returns an instance.
     *
     * @param array $attr The product media gallery attributes
     *
     * @return array The initialized product media gallery
     */
    protected function initializeProductMediaGallery(array $attr)
    {

        // load the value and the attribute ID
        $value = $attr[MemberNames::VALUE];
        $attributeId = $attr[MemberNames::ATTRIBUTE_ID];

        // query whether the product media gallery entity already exists or not
        if ($entity = $this->loadProductMediaGallery($attributeId, $value)) {
            return $this->mergeEntity($entity, $attr);
        }

        // simply return the attributes
        return $attr;
    }

    /**
     * Initialize the product media gallery value to entity with the passed attributes and returns an instance.
     *
     * @param array $attr The product media gallery value to entity attributes
     *
     * @return array|null The initialized product media gallery value to entity, or NULL if the product media gallery value to entity already exists
     */
    protected function initializeProductMediaGalleryValueToEntity(array $attr)
    {

        // load the row/value ID
        $rowId = $attr[MemberNames::ROW_ID];
        $valueId = $attr[MemberNames::VALUE_ID];

        // query whether the product media gallery value to entity entity already exists or not
        if ($this->loadProductMediaGalleryValueToEntityByValueIdAndRowId($valueId, $rowId)) {
            return;
        }

        // simply return the attributes
        return $attr;
    }

    /**
     * Load's the product media gallery with the passed attribute ID + value.
     *
     * @param integer $attributeId The attribute ID of the product media gallery to load
     * @param string  $value       The value of the product media gallery to load
     *
     * @return array The product media gallery
     */
    protected function loadProductMediaGallery($attributeId, $value)
    {
        return $this->getProductMediaProcessor()->loadProductMediaGallery($attributeId, $value);
    }

    /**
     * Load's the product media gallery with the passed value/entity ID.
     *
     * @param integer $valueId The value ID of the product media gallery value to entity to load
     * @param integer $rowId   The row ID of the product media gallery value to entity to load
     *
     * @return array The product media gallery
     */
    protected function loadProductMediaGalleryValueToEntityByValueIdAndRowId($valueId, $rowId)
    {
        return $this->getProductMediaProcessor()->loadProductMediaGalleryValueToEntityByValueIdAndRowId($valueId, $rowId);
    }
}
