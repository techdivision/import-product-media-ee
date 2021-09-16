<?php

/**
 * TechDivision\Import\Product\Media\Ee\\Utils\SqlStatementKeys
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-media-ee
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Media\Ee\Utils;

/**
 * Utility class with the SQL statements to use.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-media-ee
 * @link      http://www.techdivision.com
 */
class SqlStatementKeys extends \TechDivision\Import\Product\Media\Utils\SqlStatementKeys
{

    /**
     * The SQL statement to load an existing product media gallery by value/store/entity ID.
     *
     * @var string
     */
    const PRODUCT_MEDIA_GALLERY_VALUE = 'product_media_gallery_value';

    /**
     * The SQL statement to load an existing product media gallery value to entity by value/entity ID.
     *
     * @var string
     */
    const PRODUCT_MEDIA_GALLERY_VALUE_TO_ENTITY = 'product_media_gallery_value_to_entity';

    /**
     * The SQL statement to create a new product media gallery value entry.
     *
     * @var string
     */
    const CREATE_PRODUCT_MEDIA_GALLERY_VALUE = 'create.product_media_gallery_value';

    /**
     * The SQL statement to update an existing product media gallery value entry.
     *
     * @var string
     */
    const UPDATE_PRODUCT_MEDIA_GALLERY_VALUE = 'update.product_media_gallery_value';

    /**
     * The SQL statement to create a new product media gallery value to entity entry.
     *
     * @var string
     */
    const CREATE_PRODUCT_MEDIA_GALLERY_VALUE_TO_ENTITY = 'create.product_media_gallery_value_to_entity';
}
