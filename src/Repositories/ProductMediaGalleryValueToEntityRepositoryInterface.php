<?php

/**
 * TechDivision\Import\Product\Media\Ee\Repositories\ProductMediaGalleryValueToEntityRepositoryInterface
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-media-ee
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Media\Ee\Repositories;

/**
 * Interface for repository implementations to load product media gallery value to entity data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-media-ee
 * @link      http://www.techdivision.com
 */
interface ProductMediaGalleryValueToEntityRepositoryInterface extends \TechDivision\Import\Product\Media\Repositories\ProductMediaGalleryValueToEntityRepositoryInterface
{

    /**
     * Load's the product media gallery with the passed value/row ID.
     *
     * @param integer $valueId The value ID of the product media gallery value to entity to load
     * @param integer $rowId   The row ID of the product media gallery value to entity to load
     *
     * @return array The product media gallery
     */
    public function findOneByValueIdAndRowId($valueId, $rowId);
}
