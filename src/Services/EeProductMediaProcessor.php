<?php

/**
 * TechDivision\Import\Product\Media\Ee\Services\EeProductMediaProcessor
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
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Media\Ee\Services;

use TechDivision\Import\Actions\ActionInterface;
use TechDivision\Import\Connection\ConnectionInterface;
use TechDivision\Import\Product\Media\Services\ProductMediaProcessor;
use TechDivision\Import\Product\Media\Repositories\ProductMediaGalleryRepositoryInterface;
use TechDivision\Import\Product\Media\Ee\Repositories\ProductMediaGalleryValueRepositoryInterface;
use TechDivision\Import\Product\Media\Ee\Repositories\ProductMediaGalleryValueToEntityRepositoryInterface;

/**
 * A processor implementation that provides media import functionality for the Magento 2 EE.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class EeProductMediaProcessor extends ProductMediaProcessor implements EeProductMediaProcessorInterface
{

    /**
     * Initialize the processor with the necessary assembler and repository instances.
     *
     * @param \TechDivision\Import\Connection\ConnectionInterface                                                    $connection                                 The connection to use
     * @param \TechDivision\Import\Product\Media\Repositories\ProductMediaGalleryRepositoryInterface                 $productMediaGalleryRepository              The product media gallery repository to use
     * @param \TechDivision\Import\Product\Media\Ee\Repositories\ProductMediaGalleryValueRepositoryInterface         $productMediaGalleryValueRepository         The product media gallery value repository to use
     * @param \TechDivision\Import\Product\Media\Ee\Repositories\ProductMediaGalleryValueToEntityRepositoryInterface $productMediaGalleryValueToEntityRepository The product media gallery value to entity repository to use
     * @param \TechDivision\Import\Actions\ActionInterface                                                           $productMediaGalleryAction                  The product media gallery action to use
     * @param \TechDivision\Import\Actions\ActionInterface                                                           $productMediaGalleryValueAction             The product media gallery value action to use
     * @param \TechDivision\Import\Actions\ActionInterface                                                           $productMediaGalleryValueToEntityAction     The product media gallery value to entity action to use
     */
    public function __construct(
        ConnectionInterface $connection,
        ProductMediaGalleryRepositoryInterface $productMediaGalleryRepository,
        ProductMediaGalleryValueRepositoryInterface $productMediaGalleryValueRepository,
        ProductMediaGalleryValueToEntityRepositoryInterface $productMediaGalleryValueToEntityRepository,
        ActionInterface $productMediaGalleryAction,
        ActionInterface $productMediaGalleryValueAction,
        ActionInterface $productMediaGalleryValueToEntityAction
    ) {
        $this->setConnection($connection);
        $this->setProductMediaGalleryRepository($productMediaGalleryRepository);
        $this->setProductMediaGalleryValueRepository($productMediaGalleryValueRepository);
        $this->setProductMediaGalleryValueToEntityRepository($productMediaGalleryValueToEntityRepository);
        $this->setProductMediaGalleryAction($productMediaGalleryAction);
        $this->setProductMediaGalleryValueAction($productMediaGalleryValueAction);
        $this->setProductMediaGalleryValueToEntityAction($productMediaGalleryValueToEntityAction);
    }

    /**
     * Load's the product media gallery with the passed value/row ID.
     *
     * @param integer $valueId The value ID of the product media gallery value to entity to load
     * @param integer $rowId   The row ID of the product media gallery value to entity to load
     *
     * @return array The product media gallery
     */
    public function loadProductMediaGalleryValueToEntityByValueIdAndRowId($valueId, $rowId)
    {
        return $this->getProductMediaGalleryValueToEntityRepository()->findOneByValueIdAndRowId($valueId, $rowId);
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
    public function loadProductMediaGalleryValueByValueIdAndStoreIdAndRowId($valueId, $storeId, $rowId)
    {
        return $this->getProductMediaGalleryValueRepository()->findOneByValueIdAndStoreIdAndRowId($valueId, $storeId, $rowId);
    }
}
