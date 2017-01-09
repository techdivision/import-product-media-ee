<?php

/**
 * TechDivision\Import\Product\Media\Ee\Services\ProductMediaProcessorInterface
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

namespace TechDivision\Import\Product\Media\Ee\Services;

use TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface;

/**
 * The interface for a processor that provides media import functionality for the Magento 2 EE.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media-ee
 * @link      http://www.techdivision.com
 */
interface EeProductMediaProcessorInterface extends ProductMediaProcessorInterface
{

    /**
     * Load's the product media gallery with the passed value/row ID.
     *
     * @param integer $valueId The value ID of the product media gallery value to entity to load
     * @param integer $rowId   The row ID of the product media gallery value to entity to load
     *
     * @return array The product media gallery
     */
    public function loadProductMediaGalleryValueToEntityByValueIdAndRowId($valueId, $rowId);

    /**
     * Load's the product media gallery value with the passed value/store/row ID.
     *
     * @param integer $valueId The value ID of the product media gallery value to load
     * @param string  $storeId The store ID of the product media gallery value to load
     * @param string  $rowId   The row ID of the parent product of the product media gallery value to load
     *
     * @return array The product media gallery value
     */
    public function loadProductMediaGalleryValueByValueIdAndStoreIdAndRowId($valueId, $storeId, $rowId);
}
