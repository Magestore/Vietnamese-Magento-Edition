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
class Magestore_Regionmanager_Block_Adminhtml_Region_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Magestore_Regionmanager_Block_Adminhtml_Region_Edit_Tab_Form
     */
    protected function _prepareForm() {
		$form = new Varien_Data_Form();
		$this->setForm($form);
		
		$countries = Mage::getSingleton('directory/country')->getCollection()
			->loadData()->toOptionArray(false);

		$fieldSet = $form->addFieldset('region_form', array('legend' => Mage::helper('regionmanager')->__('Region Information')));
		
		$fieldSet->addField('country_id', 'select', 
			array(
				'label'    => Mage::helper('regionmanager')->__('Country'),
				'name'     => 'country_id',
				'required' => true,
				'values'   => $countries
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
		
		$locales = Mage::helper('regionmanager')->getLocales();
		foreach ($locales as $locale) {
			$fieldSet{$locale} = $form->addFieldset('regionmanager_form_' . $locale, array('legend' => Mage::helper('regionmanager')->__('Locale ' . $locale)));
			$fieldSet{$locale}->addField(
				'name_'.$locale, 'text', 
				array(
					'label' => Mage::helper('regionmanager')->__('Name'),
					'name'  => 'name_'.$locale,
				)
			);
		}
		
		
		if (Mage::getSingleton('adminhtml/session')->getRegionData()) {
			$data = Mage::getSingleton('adminhtml/session')->getRegionData(); 
			$form->setValues($data);
			Mage::getSingleton('adminhtml/session')->setRegionData(null);
		} elseif (Mage::registry('region_data')) {
			$data = Mage::registry('region_data')->getData(); 
			$form->setValues($data);
		}
		
		if ($id =  $this->getRequest()->getParam('id')) {
			$resource = Mage::getSingleton('core/resource');
			$read = $resource->getConnection('core_read');
			$regionName = $resource->getTableName('directory/country_region_name');
			$select = $read->select()->from(array('region'=>$regionName))->where('region.region_id=?', $id);
			$data =$read->fetchAll($select);
			foreach($data as $row) {
				$form->addValues(array('name_'.$row['locale']=> $row['name']));
			}
		} 
		
		return parent::_prepareForm();

	}
}