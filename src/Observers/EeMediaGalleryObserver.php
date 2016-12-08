<?php

/**
 * TechDivision\Import\Product\Media\Ee\Observers\EeMediaGalleryObserver
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

use TechDivision\Import\Product\Media\Utils\ColumnKeys;
use TechDivision\Import\Product\Media\Observers\MediaGalleryObserver;

/**
 * A SLSB that handles the process to import product media.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */
class EeMediaGalleryObserver extends MediaGalleryObserver
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
        if ($this->isParentImage($row[$headers[ColumnKeys::IMAGE_PATH]])) {
            return $row;
        }

        // load the product SKU
        $parentSku = $row[$headers[ColumnKeys::IMAGE_PARENT_SKU]];

        // load parent/option ID
        $parentId = $this->mapSkuToRowId($parentSku);

        // reset the position counter for the product media gallery value
        $this->resetPositionCounter();

        // preserve the parent ID
        $this->setParentId($parentId);

        // initialize the gallery data
        $disabled = 0;
        $attributeId = 90;
        $mediaType = 'image';
        $image = $row[$headers[ColumnKeys::IMAGE_PATH_NEW]];

        // persist the product media gallery data
        $valueId = $this->persistProductMediaGallery(array($attributeId, $image, $mediaType, $disabled));

        // persist the product media gallery to entity data
        $this->persistProductMediaGalleryValueToEntity(array($valueId, $parentId));

        // temporarily persist the value ID
        $this->setParentValueId($valueId);

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
