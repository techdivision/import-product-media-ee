<?php

/**
 * TechDivision\Import\Product\Media\Ee\Observers\EeMediaGalleryValueObserver
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

use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Product\Media\Utils\ColumnKeys;
use TechDivision\Import\Product\Media\Ee\Utils\MemberNames;
use TechDivision\Import\Product\Media\Observers\MediaGalleryValueObserver;

/**
 * Observer that provides extended mapping functionality to map a SKU to a row ID (EE Feature).
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media-ee
 * @link      http://www.techdivision.com
 */
class EeMediaGalleryValueObserver extends MediaGalleryValueObserver
{

    /**
     * Prepare the product media gallery value that has to be persisted.
     *
     * @return array The prepared product media gallery value attributes
     */
    protected function prepareAttributes()
    {

        // load the product SKU and map it the entity ID
        $parentId = $this->getValue(ColumnKeys::IMAGE_PARENT_SKU, null, array($this, 'mapParentSku'));

        // load the store ID
        $storeId = $this->getRowStoreId(StoreViewCodes::ADMIN);

        // load the value ID and the position counter
        $valueId = $this->getParentValueId();
        $position = $this->raisePositionCounter();

        // load the image label
        $imageLabel = $this->getValue(ColumnKeys::IMAGE_LABEL);

        // load the flag that decides whether or not an image should be hidden on product page
        $hideFromProductPage = $this->getValue(ColumnKeys::HIDE_FROM_PRODUCT_PAGE);

        // prepare the media gallery value
        return $this->initializeEntity(
            array(
                MemberNames::VALUE_ID    => $valueId,
                MemberNames::STORE_ID    => $storeId,
                MemberNames::ROW_ID      => $parentId,
                MemberNames::LABEL       => $imageLabel,
                MemberNames::POSITION    => $position,
                MemberNames::DISABLED    => $hideFromProductPage
            )
        );
    }

    /**
     * Map's the passed SKU of the parent product to it's PK.
     *
     * @param string $parentSku The SKU of the parent product
     *
     * @return integer The primary key used to create relations
     */
    protected function mapParentSku($parentSku)
    {
        return $this->mapSkuToRowId($parentSku);
    }

    /**
     * Return the row ID for the passed SKU.
     *
     * @param string $sku The SKU to return the row ID for
     *
     * @return integer The mapped row ID
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    protected function mapSkuToRowId($sku)
    {
        return $this->getSubject()->mapSkuToRowId($sku);
    }
}
