<?php
/**
 * Vietnam Localization package for Magento Localized
 *
 * @category   Magestore
 * @package    Magestore_MagentoLocalizedVN
 * @copyright  Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license    http://www.magestore.com/license-agreement.html
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

// translate attribute labels
$attributesSqlFilename = Mage::getBaseDir('locale') . DS . 'vi_VN' . DS . 'sql_translation' . DS . 'attributes.sql';

if (file_exists($attributesSqlFilename)) {

    // run script only if no database table prefix is set
    if ($installer->getTable('poll') == 'poll') {

        $attributesSql = file_get_contents($attributesSqlFilename);
        // question marks break the installer as they are intended as placeholders
        $attributesSql = str_replace('?', '&quest;', $attributesSql);
        $installer->run($attributesSql);
    }
}

$installer->endSetup();