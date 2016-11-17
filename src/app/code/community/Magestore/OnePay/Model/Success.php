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
 * @package     Magestore_OnePay
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * OnePay Status Model
 * 
 * @category    Magestore
 * @package     Magestore_OnePay
 * @author      Magestore Developer
 */
class Magestore_OnePay_Model_Success extends Mage_Core_Model_Abstract
{
	public function _construct(){
        parent::_construct();
        $this->_init('onepay/onepay');
		
		
    }
	public function loadByIncrementId($incrementId){
        return $this->loadByAttribute('vpc_OrderInfo', $incrementId);
    }

    /**
     * Load order by custom attribute value. Attribute value should be unique
     *
     * @param string $attribute
     * @param string $value
     * @return Mage_Sales_Model_Order
     */
    public function loadByAttribute($attribute, $value){
        $this->load($value, $attribute);
        return $this;
    }
}