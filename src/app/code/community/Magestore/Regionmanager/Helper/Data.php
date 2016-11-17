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
 * Regionmanager Helper
 * 
 * @category    Magestore
 * @package     Magestore_Regionmanager
 * @author      Magestore Developer
 */
class Magestore_Regionmanager_Helper_Data extends Mage_Core_Helper_Abstract
{
	protected $_cityJson;
	public function getLocales() {
		$stores = Mage::app()->getStores();
		$locales = array();
		foreach ($stores as $store) {
				$v = Mage::getStoreConfig('general/locale/code', $store->getId());
				$locales[$v] = $v;
		}
		return $locales;
	}
	public function getCityJson() { 
		$storeId = Mage::app()->getStore()->getId();
		if (!$this->_cityJson) {
			$cacheKey = 'regionmanager_city_store_' . (string)$storeId;
			if (Mage::app()->useCache('config')) {
				$json = Mage::app()->loadCache($cacheKey);
			}
			if (empty($json)) {
				$cities = $this->_getCities($storeId);
				$helper = Mage::helper('core');
				$json = $helper->jsonEncode($cities);

				if (Mage::app()->useCache('config')) {
					Mage::app()->saveCache($json, $cacheKey, array('config'));
				}
			}
			$this->_cityJson = $json;
		}
		return $this->_cityJson;
	}
	
	protected function _getCities($storeId) {
		$cities = array();
		$collection = Mage::getSingleton('regionmanager/city')->getCollection();	
		foreach ($collection as $item) {
			$itemData = $item->getData();
			$cities[$itemData['region_id']][$itemData['city_id']] = array(
				/* 'code'	=> $itemData['city_id'], */
				'code'	=> $itemData['default_name'],
				'name'	=> $itemData['default_name']
			);
		}
		return $cities;
	}
    
}