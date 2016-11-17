<?php

class Magestore_Regionmanager_Block_Adminhtml_City_Import_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm(){
		$form = new Varien_Data_Form(array(
			'id'	=> 'edit_form',
			'action'	=> $this->getUrl('*/*/processImport'),
			'method'	=> 'post',
			'enctype'	=> 'multipart/form-data'
		));
		
		$fieldset = $form->addFieldset('profile_fieldset',array('legend'=>Mage::helper('regionmanager')->__('Import City')));
		
		$fieldset->addField('filecsv','file',array(
			'label'		=> Mage::helper('regionmanager')->__('Import File'),
			'title'		=> Mage::helper('regionmanager')->__('Import File'),
			'name'		=> 'filecsv',
			'required'	=> true,
		));
        
        $fieldset->addField('sample', 'note', array(
            'label' => Mage::helper('regionmanager')->__('Download Sample CSV File'),
            'text'  => '<a href="'.
                    $this->getUrl('*/*/downloadSampleHN').
                    '" title="'.
                    Mage::helper('regionmanager')->__('Download Sample CSV File').
                    '">import_city_vn_HN.csv</a><br/><a href="'.
                    $this->getUrl('*/*/downloadSampleHCM').
                    '" title="'.
                    Mage::helper('regionmanager')->__('Download Sample CSV File').
                    '">import_city_vn_HCM.csv</a>'
        ));
		
		$form->setUseContainer(true);
		$this->setForm($form);
		return parent::_prepareForm();
	}
}