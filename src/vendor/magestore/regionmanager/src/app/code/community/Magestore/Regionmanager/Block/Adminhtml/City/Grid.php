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
class Magestore_Regionmanager_Block_Adminhtml_City_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
		$this->setId('cityGrid');
		$this->setDefaultSort('city_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
    }
    
    /**
     * prepare collection for block to display
     *
     * @return Magestore_Regionmanager_Block_Adminhtml_Regionmanager_Grid
     */
    protected function _prepareCollection() {
		$collection = Mage::getModel('regionmanager/city')->getCollection();
		$collection->getSelect()->join(
			array('region' => Mage::getSingleton('core/resource')->getTableName('directory_country_region')), 
			'main_table.region_id=region.region_id',
			array('region.default_name as region_name', 'region.country_id as country_id')
		);
		$this->setCollection($collection);
		return parent::_prepareCollection();
  }
    
    /**
     * prepare columns for this grid
     *
     * @return Magestore_Regionmanager_Block_Adminhtml_Regionmanager_Grid
     */
    
	protected function _prepareColumns() {				
		$this->addColumn('city_id', array(
			'header'    => Mage::helper('regionmanager')->__('ID'),
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'city_id',
			'type'		=> 'number'
		));
		
		$this->addColumn(
			'country_id', array(
				'header' => Mage::helper('regionmanager')->__('Country Code'),
				'align'  => 'left',
				'width'  => '110px',
				'index'  => 'country_id',
				'type'   => 'country',
				'filter_index' => 'region.country_id'
			)
		);
		
		$this->addColumn('region_name', array(
			'header'    => Mage::helper('regionmanager')->__('Region'),
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'region_name',
			'filter_index' => 'region.region_name'
		));
		
		$this->addColumn('code', array(
			'header'           => Mage::helper('regionmanager')->__('City Code'),
			'align'            => 'left',
			'width'            => '110px',
			'index'            => 'code',
			'column_css_class' => 'code_td',
			'filter_index'		=> 'main_table.code'
		));
		
		$this->addColumn('default_name', array(
			'header'           => Mage::helper('regionmanager')->__('Default Name'),
			'align'            => 'left',
			'width'            => '110px',
			'index'            => 'default_name',
			'column_css_class' => 'default_name',
			'filter_index' => 'main_table.default_name'
		));
	  
		$this->addColumn('action',
			array(
				'header'    =>  Mage::helper('regionmanager')->__('Action'),
				'width'     => '100',
				'type'      => 'action',
				'getter'    => 'getId',
				'actions'   => array(
					array(
						'caption'   => Mage::helper('regionmanager')->__('Edit'),
						'url'       => array('base'=> '*/*/edit'),
						'field'     => 'id'
					)
				),
				'filter'    => false,
				'sortable'  => false,
				'index'     => 'stores',
				'is_system' => true,
		));
	  
        $this->addExportType('*/*/exportCsv', Mage::helper('regionmanager')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('regionmanager')->__('XML'));
	  
		return parent::_prepareColumns();
	}

	protected function _prepareMassaction() {
		$this->setMassactionIdField('city_id');
		$this->getMassactionBlock()->setFormFieldName('city');

		$this->getMassactionBlock()->addItem('delete', array(
			'label'    => Mage::helper('regionmanager')->__('Delete'),
			'url'      => $this->getUrl('*/*/massDelete'),
			'confirm'  => Mage::helper('regionmanager')->__('Are you sure?')
		));
		
		return $this;
	}
    
    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}