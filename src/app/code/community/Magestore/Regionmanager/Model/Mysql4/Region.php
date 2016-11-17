<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_Regionmanager
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Regionmanager Resource Model
 * 
 * @category    Magestore
 * @package     Magestore_Regionmanager
 * @author      Magestore Developer
 */
class Magestore_Regionmanager_Model_Mysql4_Region extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('regionmanager/region', 'region_id');
    }
    /**
     * import region from CSV
     * @param type $region
     * @throws Exception
     */
    public function importRegionFromCsv($regions) {
        $write = $this->_getWriteAdapter();
        $write->beginTransaction();
        try {
            $write->insertMultiple($this->getTable('regionmanager/region'), $regions);
            $write->commit();
        } catch (Exception $e) {
            $write->rollback();
            throw $e;
        }
        unset($region);
    }
}