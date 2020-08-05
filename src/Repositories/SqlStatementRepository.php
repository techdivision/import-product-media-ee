<?php

/**
 * TechDivision\Import\Product\Media\Ee\Repositories\SqlStatements
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

namespace TechDivision\Import\Product\Media\Ee\Repositories;

use TechDivision\Import\Product\Media\Ee\Utils\SqlStatementKeys;

/**
 * Repository class with the SQL statements to use.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media-ee
 * @link      http://www.techdivision.com
 */
class SqlStatementRepository extends \TechDivision\Import\Product\Media\Repositories\SqlStatementRepository
{

    /**
     * The SQL statements.
     *
     * @var array
     */
    private $statements = array(
        SqlStatementKeys::PRODUCT_MEDIA_GALLERY_VALUE =>
            'SELECT *
               FROM ${table:catalog_product_entity_media_gallery_value}
              WHERE value_id = :value_id
                AND store_id = :store_id
                AND row_id = :row_id',
        SqlStatementKeys::PRODUCT_MEDIA_GALLERY_VALUE_TO_ENTITY =>
            'SELECT *
               FROM ${table:catalog_product_entity_media_gallery_value_to_entity}
              WHERE value_id = :value_id
                AND row_id = :row_id',
        SqlStatementKeys::PRODUCT_MEDIA_GALLERIES_BY_SKU =>
            'SELECT t3.*
               FROM ${table:catalog_product_entity} t1,
                    ${table:catalog_product_entity_media_gallery_value_to_entity} t2,
                    ${table:catalog_product_entity_media_gallery} t3
              WHERE t1.sku = :sku
                AND t2.row_id = t1.row_id
                AND t3.value_id = t2.value_id'
    );

    /**
     * Initializes the SQL statement repository with the primary key and table prefix utility.
     *
     * @param \IteratorAggregate<\TechDivision\Import\Utils\SqlCompilerInterface> $compilers The array with the compiler instances
     */
    public function __construct(\IteratorAggregate $compilers)
    {

        // pass primary key + table prefix utility to parent instance
        parent::__construct($compilers);

        // compile the SQL statements
        $this->compile($this->statements);
    }
}
