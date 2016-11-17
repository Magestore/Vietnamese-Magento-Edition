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
 * Regionmanager Edit Form Content Tab Block
 * 
 * @category    Magestore
 * @package     Magestore_Regionmanager
 * @author      Magestore Developer
 */
class Magestore_Regionmanager_Block_Adminhtml_City_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {
  protected $_regionOptions = null; 
	
	protected function _prepareForm() {
		$form = new Varien_Data_Form();
		$this->setForm($form);
		
		if (Mage::getSingleton('adminhtml/session')->getCityData()) {
			$data = Mage::getSingleton('adminhtml/session')->getCityData();
			Mage::getSingleton('adminhtml/session')->setCityData(null);
		} elseif ( Mage::registry('city_data') ) {
			$data = Mage::registry('city_data')->getData();
		}
		
		if(isset($data['region_id']) && $data['region_id']){
			$region = Mage::getModel('regionmanager/region')->load($data['region_id']);
			$data['country_id'] = $region->getCountryId();
		}else{
			$data['region_id'] ='0';
		}
		
		$countries = Mage::getSingleton('directory/country')->getCollection()
			->loadData()->toOptionArray(false);
			
		$regions = $this->getThaiRegions();
		
		$fieldSet = $form->addFieldset('citymanager_form', array('legend'=>Mage::helper('regionmanager')->__('City Information')));
	 
		$regionUrl = $this->getUrl('*/*/reloadRegion');
		$fieldSet->addField('country_id', 'select', 
			array(
				'label'    => Mage::helper('regionmanager')->__('Country'),
				'name'     => 'country_id',
				'required' => true,
				'values'   => $countries,
				'onchange' => "reloadRegion(this.value, '$regionUrl',0)",
				'after_element_html' => "<script type='text/javascript'>Event.observe(window, 'load', function(){ reloadRegion($('country_id').value, '".$regionUrl."','".$data['region_id']."');});</script>"
			)
		);

		$fieldSet->addField('region_id', 'select', 
			array(
				'label'    => Mage::helper('regionmanager')->__('Region'),
				'name'     => 'region_id',
				'required' => true,
				'values'   => $regions,
			)
		);
		
		$fieldSet->addField('code', 'text', 
			array(
				'label'    => Mage::helper('regionmanager')->__('Code'),
				'class'    => 'required-entry',
				'required' => true,
				'name'     => 'code',
			)
		);
		$fieldSet->addField('default_name', 'text', 
			array(
				'label'    => Mage::helper('regionmanager')->__('Default Name'),
				'class'    => 'required-entry',
				'required' => true,
				'name'     => 'default_name',
			)
		);
	 
		$form->setValues($data);
		
		return parent::_prepareForm();
  }
	
	public function getThaiRegions() {
		if ($this->_regionOptions == null) {
			$this->_regionOptions[] = array('value' => '', 'label' => '');
			$regions = Mage::getSingleton('regionmanager/region')->getCollection()
				->addFieldToFilter('country_id', 'TH');
			foreach ($regions as $region) {
				$this->_regionOptions[] = array(
					'value' => $region->getRegionId(), 
					'label' => $region->getDefaultName()
				);
			}
		}
		return $this->_regionOptions;		
	}
}