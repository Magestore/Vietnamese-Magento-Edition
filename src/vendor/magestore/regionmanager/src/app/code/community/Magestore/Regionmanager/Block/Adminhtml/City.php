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
 * Regionmanager Adminhtml Block
 * 
 * @category    Magestore
 * @package     Magestore_Regionmanager
 * @author      Magestore Developer
 */
class Magestore_Regionmanager_Block_Adminhtml_City extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_city';
        $this->_blockGroup = 'regionmanager';
        $this->_headerText = Mage::helper('regionmanager')->__('City Manager');
        $this->_addButtonLabel = Mage::helper('regionmanager')->__('Add City');
        parent::__construct();
        $this->_addButton('import_city',array(
			'label'		=> Mage::helper('regionmanager')->__('Import Citys'),
			'onclick'	=> "setLocation('{$this->getUrl('*/*/import')}')",
			'class'		=> 'import'
		),-1);
    }
}