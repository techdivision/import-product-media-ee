<?php
/**
 * Copyright (c) 2026 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * All rights reserved
 *
 * This product includes proprietary software developed at TechDivision GmbH, Germany
 * For more information see https://www.techdivision.com
 *
 * To obtain a valid license for using this software, please contact us at
 * license@techdivision.com
 */
namespace TechDivision\Import\Product\Media\Ee\Observers;

use TechDivision\Import\Product\Media\Ee\Utils\MemberNames;
use TechDivision\Import\Product\Media\Observers\CleanUpMediaGalleryObserver;

/**
 * Observer that cleaned up a product's media gallery information for the Magento 2 EE.
 *
 * @copyright Copyright (c) 2026 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link http://www.techdivision.com
 * @author MET <met@techdivision.com>
 */
class EeCleanUpMediaGalleryObserver extends CleanUpMediaGalleryObserver
{
    /**
     * Return's the name of the member that holds the ID of the product a media gallery entry is assigned to (e.g.
     *  entity_id for CE, row_id for EE)
     *
     * @return string The member name
     */
    protected function getProductEntityIdMemberName()
    {
        return MemberNames::ROW_ID;
    }
}
