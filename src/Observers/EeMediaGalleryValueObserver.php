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
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */

namespace TechDivision\Import\Product\Media\Ee\Observers;

use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Product\Utils\MemberNames;
use TechDivision\Import\Product\Media\Utils\ColumnKeys;
use TechDivision\Import\Product\Media\Observers\MediaGalleryValueObserver;

/**
 * A SLSB that handles the process to import product media.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */
class EeMediaGalleryValueObserver extends MediaGalleryValueObserver
{

    /**
     * {@inheritDoc}
     * @see \Importer\Csv\Actions\Listeners\Row\ListenerInterface::handle()
     */
    public function handle(array $row)
    {

        // load the header information
        $headers = $this->getHeaders();

        // query whether or not, the image changed
        if ($this->isParentImage($image = $row[$headers[ColumnKeys::IMAGE_PATH]])) {
            return $row;
        }

        // load the product SKU
        $parentSku = $row[$headers[ColumnKeys::IMAGE_PARENT_SKU]];

        // load parent/option ID
        $parentId = $this->mapSkuToRowId($parentSku);

        // initialize the store view code
        $storeViewCode = $row[$headers[ColumnKeys::STORE_VIEW_CODE]] ?: StoreViewCodes::ADMIN;

        // load the store ID
        $store = $this->getStoreByStoreCode($storeViewCode);
        $storeId = $store[MemberNames::STORE_ID];

        // load the value ID and the position counter
        $valueId = $this->getParentValueId();
        $position = $this->raisePositionCounter();

        // load the image label
        $imageLabel = $row[$headers[ColumnKeys::IMAGE_LABEL]];

        // initialize the disabled flag
        $disabled = 0;

        // prepare the media gallery value
        $productMediaGalleryValue = array(
            $valueId,
            $storeId,
            $parentId,
            $imageLabel,
            $position,
            $disabled
        );

        // persist the product media gallery value
        $this->persistProductMediaGalleryValue($productMediaGalleryValue);

        // temporarily persist the image name
        $this->setParentImage($image);

        // returns the row
        return $row;
    }

    /**
     * Return the row ID for the passed SKU.
     *
     * @param string $sku The SKU to return the row ID for
     *
     * @return integer The mapped row ID
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    public function mapSkuToRowId($sku)
    {
        return $this->getSubject()->mapSkuToRowId($sku);
    }
}
