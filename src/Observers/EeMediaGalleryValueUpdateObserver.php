<?php

/**
 * TechDivision\Import\Product\Media\Ee\Observers\EeMediaGalleryValueUpdateObserver
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
class EeMediaGalleryValueUpdateObserver extends EeMediaGalleryValueObserver
{

    /**
     * Initialize the product media gallery value with the passed attributes and returns an instance.
     *
     * @param array $attr The product media gallery value attributes
     *
     * @return array The initialized product media gallery value
     */
    protected function initializeProductMediaGalleryValue(array $attr)
    {

        // load the row/value/store ID
        $rowId = $attr[MemberNames::ROW_ID];
        $valueId = $attr[MemberNames::VALUE_ID];
        $storeId = $attr[MemberNames::STORE_ID];

        // query whether the product media gallery value already exists or not
        if ($entity = $this->loadProductMediaGalleryValueByValueIdAndStoreIdAndRowId($valueId, $storeId, $rowId)) {
            return $this->mergeEntity($entity, $attr);
        }

        // simply return the attributes
        return $attr;
    }

    /**
     * Load's the product media gallery value with the passed value/store/row ID.
     *
     * @param integer $valueId The value ID of the product media gallery value to load
     * @param string  $storeId The store ID of the product media gallery value to load
     * @param string  $rowId   The row ID of the parent product of the product media gallery value to load
     *
     * @return array The product media gallery value
     */
    protected function loadProductMediaGalleryValueByValueIdAndStoreIdAndRowId($valueId, $storeId, $rowId)
    {
        return $this->getProductMediaProcessor()->loadProductMediaGalleryValueByValueIdAndStoreIdAndRowId($valueId, $storeId, $rowId);
    }
}
