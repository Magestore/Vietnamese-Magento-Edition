<?php

class Magestore_Regionmanager_Block_Adminhtml_Region_Import extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_blockGroup = 'regionmanager';
        $this->_controller = 'adminhtml_region';
        $this->_mode = 'import';
        $this->_updateButton('save', 'label', Mage::helper('regionmanager')->__('Import'));
        $this->_removeButton('delete');
        $this->_removeButton('reset');
    }

    public function getHeaderText() {
        return Mage::helper('regionmanager')->__('Import Region');
    }

}