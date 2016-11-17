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
 * Regionmanager Edit Block
 * 
 * @category     Magestore
 * @package     Magestore_Regionmanager
 * @author      Magestore Developer
 */
class Magestore_Regionmanager_Block_Adminhtml_City_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'regionmanager';
        $this->_controller = 'adminhtml_city';
        
        $this->_updateButton('save', 'label', Mage::helper('regionmanager')->__('Save City'));
        $this->_updateButton('delete', 'label', Mage::helper('regionmanager')->__('Delete City'));
        
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
			function reloadRegion(countryId, regionUrl,regionId) {
				loader = new varienLoader(true);
				loader.load(regionUrl, {country_id: countryId,region_id: regionId}, onReloadRegion.bind(this));
			}

			function onReloadRegion(serverResponse) {
				if (!serverResponse)
				return;
				var data = eval('(' + serverResponse + ')');
				var regionSelect = $('region_id');
				if (regionSelect && data.options) {
					regionSelect.update(data.options);
					if(data.region_id)
						regionSelect.value = data.region_id;
				}
			}
        ";
    }
    
    /**
     * get text to show in header when edit an city
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('city_data')
            && Mage::registry('city_data')->getRegionId()
        ) {
            return Mage::helper('regionmanager')->__("Edit City '%s'",
                                                $this->htmlEscape(Mage::registry('city_data')->getDefaultName())
            );
        }
        return Mage::helper('regionmanager')->__('Add City');
    }
}