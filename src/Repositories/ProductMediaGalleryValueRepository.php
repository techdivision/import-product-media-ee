<?php

/**
 * TechDivision\Import\Product\Media\Ee\Repositories\ProductMediaGalleryValueRepository
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

use TechDivision\Import\Product\Media\Ee\Utils\MemberNames;
use TechDivision\Import\Product\Media\Ee\Utils\SqlStatementKeys;

/**
 * Repository implementation to load product media gallery value data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-media-ee
 * @link      http://www.techdivision.com
 */
class ProductMediaGalleryValueRepository extends \TechDivision\Import\Product\Media\Repositories\ProductMediaGalleryValueRepository implements ProductMediaGalleryValueRepositoryInterface
{

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {
        // initialize the prepared statements
        $this->addFinder($this->finderFactory->createFinder($this, SqlStatementKeys::PRODUCT_MEDIA_GALLERY_VALUE));
        $this->addFinder($this->finderFactory->createFinder($this, SqlStatementKeys::PRODUCT_MEDIA_GALLERY_VALUES));
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
    public function findOneByValueIdAndStoreIdAndRowId($valueId, $storeId, $rowId)
    {

        // initialize the params
        $params = array(
            MemberNames::VALUE_ID  => $valueId,
            MemberNames::STORE_ID  => $storeId,
            MemberNames::ROW_ID    => $rowId
        );

        // load and return the product media gallery value with the passed value/store/parent ID
        return $this->getFinder(SqlStatementKeys::PRODUCT_MEDIA_GALLERY_VALUE)->find($params);
    }
}
