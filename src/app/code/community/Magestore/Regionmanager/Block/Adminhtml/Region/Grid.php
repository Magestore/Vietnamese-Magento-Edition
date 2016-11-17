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
class Magestore_Regionmanager_Block_Adminhtml_Region_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
		$this->setId('regionGrid');
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
		$this->addColumn('region_id', array(
			'header'    => Mage::helper('regionmanager')->__('ID'),
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'region_id',
			'type'		=> 'number'
		));
		
		$this->addColumn(
			'country_id', array(
				'header' => Mage::helper('regionmanager')->__('Country Code'),
				'align'  => 'left',
				'width'  => '110px',
				'index'  => 'country_id',
				'type'   => 'country',
			)
		);
		
		$this->addColumn(
			'code', array(
				'header'           => Mage::helper('regionmanager')->__('Region Code'),
				'align'            => 'left',
				'width'            => '110px',
				'index'            => 'code',
				'column_css_class' => 'code_td'
			)
		);
		
		$this->addColumn(
			'default_name', array(
				'header'           => Mage::helper('regionmanager')->__('Default Name'),
				'align'            => 'left',
				'width'            => '110px',
				'index'            => 'default_name',
				'column_css_class' => 'default_name'
			)
		);
		$this->addColumn(
			'name_locale', array(
				'header'           => Mage::helper('regionmanager')->__('Name in Locale'),
				'align'            => 'left',
				'width'            => '110px',
				'index'            => 'region_id',
				'sortable'         => false,
				'filter'           => false,
				'renderer'  =>     'Magestore_Regionmanager_Block_Adminhtml_Region_Renderer_Name',
				'column_css_class' => 'name_locale'
			)
		);
				
	  
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
						'field'     => 'region_id'
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
		$this->setMassactionIdField('region_id');
		$this->getMassactionBlock()->setFormFieldName('region');
		$this->getMassactionBlock()->setUseSelectAll(false);
		$this->getMassactionBlock()->addItem(
			'delete', 
			array(
				'label' => Mage::helper('regionmanager')->__('Delete'),
				'url'   => $this->getUrl('*/*/massDelete', array('_current' => true)),
			)
		);
		
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