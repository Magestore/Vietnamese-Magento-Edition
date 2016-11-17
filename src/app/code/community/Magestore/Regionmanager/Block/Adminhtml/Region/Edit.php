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
class Magestore_Regionmanager_Block_Adminhtml_Region_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'regionmanager';
        $this->_controller = 'adminhtml_region';
        
        $this->_updateButton('save', 'label', Mage::helper('regionmanager')->__('Save Region'));
        $this->_updateButton('delete', 'label', Mage::helper('regionmanager')->__('Delete Region'));
        
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    
    /**
     * get text to show in header when edit an Region
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('region_data')
            && Mage::registry('region_data')->getRegionId()
        ) {
            return Mage::helper('regionmanager')->__("Edit Region '%s'",
                                                $this->htmlEscape(Mage::registry('region_data')->getDefaultName())
            );
        }
        return Mage::helper('regionmanager')->__('Add Region');
    }
}