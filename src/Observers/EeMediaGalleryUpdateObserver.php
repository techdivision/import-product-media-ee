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
