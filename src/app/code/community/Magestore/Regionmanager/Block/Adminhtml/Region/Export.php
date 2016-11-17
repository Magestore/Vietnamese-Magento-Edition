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
 * Regionmanager Grid Block
 * 
 * @category    Magestore
 * @package     Magestore_Regionmanager
 * @author      Magestore Developer
 */
class Magestore_Regionmanager_Block_Adminhtml_Region_Export extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('regionmanagerGrid');
        $this->setDefaultSort('region_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    
    /**
     * prepare collection for block to display
     *
     * @return Magestore_Regionmanager_Block_Adminhtml_Regionmanager_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('regionmanager/region')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * prepare columns for this grid
     *
     * @return Magestore_Regionmanager_Block_Adminhtml_Regionmanager_Grid
     */

	protected function _prepareColumns() {
		
		$this->addColumn(
			'country_id', array(
				'header' => Mage::helper('regionmanager')->__('country_id'),
				'align'  => 'left',
				'width'  => '110px',
				'index'  => 'country_id',
			)
		);
		
		$this->addColumn(
			'code', array(
				'header'           => Mage::helper('regionmanager')->__('code'),
				'align'            => 'left',
				'width'            => '110px',
				'index'            => 'code',
				'column_css_class' => 'code_td'
			)
		);
		
		$this->addColumn(
			'default_name', array(
				'header'           => Mage::helper('regionmanager')->__('default_name'),
				'align'            => 'left',
				'width'            => '110px',
				'index'            => 'default_name',
				'column_css_class' => 'default_name'
			)
		);
		return parent::_prepareColumns();
	}
}