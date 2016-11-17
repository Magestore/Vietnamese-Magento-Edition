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
 * Regionmanager Adminhtml Controller
 * 
 * @category    Magestore
 * @package     Magestore_Regionmanager
 * @author      Magestore Developer
 */
class Magestore_Regionmanager_Adminhtml_Regionmanager_CityController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Magestore_Regionmanager_Adminhtml_RegionmanagerController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('regionmanager/city')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Citys Manager'),
                Mage::helper('adminhtml')->__('City Manager')
            );
        return $this;
    }
 
    /**
     * index action
     */
    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    /**
     * view and edit item action
     */
    public function editAction()
    {
        $cityId     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('regionmanager/city')->load($cityId);

        if ($model->getId() || $cityId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('city_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('regionmanager/city');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('City Manager'),
                Mage::helper('adminhtml')->__('City Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('City News'),
                Mage::helper('adminhtml')->__('City News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('regionmanager/adminhtml_city_edit'))
                ->_addLeft($this->getLayout()->createBlock('regionmanager/adminhtml_city_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('regionmanager')->__('City does not exist')
            );
            $this->_redirect('*/*/');
        }
    }
 
    public function newAction()
    {
        $this->_forward('edit');
    }
 
    /**
     * save item action
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
              
            $model = Mage::getModel('regionmanager/city');        
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            
            try {
                $model->save();
								
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('regionmanager')->__('City was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('regionmanager')->__('Unable to find region to save')
        );
        $this->_redirect('*/*/');
    }
 
    /**
     * delete item action
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('regionmanager/city');
                $model->setId($this->getRequest()->getParam('id'));
				
               $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('City was successfully deleted')
                );
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * mass delete item(s) action
     */
    public function massDeleteAction()
    {
        $cityIds = $this->getRequest()->getParam('city');
        if (!is_array($cityIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select city(s)'));
        } else {
            try {
                foreach ($cityIds as $city) {
                    $citymanager = Mage::getModel('regionmanager/city')->load($city);
					$citymanager->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d city(s) were successfully deleted',
                    count($cityIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
	public function reloadRegionAction() {
		$countryId = $this->getRequest()->getParam('country_id', false);
		$region_id = $this->getRequest()->getParam('region_id', 0);
		$options = '';
		$result = array('success' => false);
		$result['region_id'] = $region_id;
		if ($countryId) {			
			$collection = Mage::getModel('regionmanager/region')->getCollection()
				->addFieldToFilter('country_id', $countryId);
			if (count($collection)) {
				$options .= '<option value="">'.Mage::helper('adminhtml')->__("Please Select").'</option>'; 
				foreach ($collection as $region) {
					$code = $region->getRegionId();
					$regionName = $region->getDefaultName();
					$options .= "<option value='$code'>$regionName</option>";
				}
				$result['success'] = true;
				$result['options'] = $options;
			}else{
				$options .= '<option value="">'.Mage::helper('adminhtml')->__("Please Create Region for this country!").'</option>'; 
				$result['options'] = $options;
			}
		}		
		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}

    public function importAction() {
        $this->loadLayout()
                ->_setActiveMenu('regionmanager/city');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('regionmanager/adminhtml_city_import'));
        $this->_title($this->__('City Manager'))
                ->_title($this->__('Import City'));
        $this->renderLayout();
    }

    public function processImportAction() {
        if (isset($_FILES['filecsv']['name']) && $_FILES['filecsv']['name'] != '') {
            try {
                /* Starting upload */
                $uploader = new Varien_File_Uploader('filecsv');

                // Any extention would work
                $uploader->setAllowedExtensions(array('csv'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
				
                $fileName = $_FILES['filecsv']['tmp_name'];

                if (isset($fileName) && $fileName != '') {
                    $csvObject = new Varien_File_Csv();
                    $dataFile = $csvObject->getData($fileName);
                    $cityData = array();
					$count = 0;
                    foreach ($dataFile as $row => $cols) {
                        if ($row == 0) {
                            $fields = $cols;
                        } else {
							$array = array_combine($fields, $cols);
							if(isset($array['code']) && isset($array['default_name']) && isset($array['region_code']) && $region_id = Mage::getModel('regionmanager/region')->load($array['region_code'],'code')->getId()){
								unset($array['region_code']);
								$array['region_id'] = $region_id;
								$cityData[] = $array;
							}
								
                        }
						if(count($cityData) > 1000){
							Mage::getResourceModel('regionmanager/city')->importCityFromCsv($cityData);
							$count += count($cityData);
							$cityData = array();
						}
                    }
					if(count($cityData)){
							Mage::getResourceModel('regionmanager/city')->importCityFromCsv($cityData);
							$count += count($cityData);
					}
                }
				
                if (isset($count) && $count) {
                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Imported total %d city(s)', $count));
                    $this->_redirect('*/*/index');
                    return $this;
                } else {
                    Mage::getSingleton('adminhtml/session')->addError($this->__('invalid files'));
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        } else {
            Mage::getSingleton('adminhtml/session')->addError($this->__('No uploaded files'));
        }
        $this->_redirect('*/*/import');
    }
	
	/**
     * download simple csv
     */
    public function downloadSampleHNAction() {
        $filename = Mage::getBaseDir('media') . DS . 'regionmanager' . DS . 'import_city_vn_HN.csv';
        $this->_prepareDownloadResponse('import_city_vn_HN.csv', file_get_contents($filename));
    }
	
	/**
     * download simple csv
     */
    public function downloadSampleHCMAction() {
        $filename = Mage::getBaseDir('media') . DS . 'regionmanager' . DS . 'import_city_vn_HCM.csv';
        $this->_prepareDownloadResponse('import_city_vn_HCM.csv', file_get_contents($filename));
    }

    /**
     * export grid item to CSV type
     */
    public function exportCsvAction()
    {
        $fileName   = 'citymanager.csv';
        $content    = $this->getLayout()
                           ->createBlock('regionmanager/adminhtml_city_export')
                           ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName   = 'recitymanager.xml';
        $content    = $this->getLayout()
                           ->createBlock('regionmanager/adminhtml_city_export')
                           ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('regionmanager/city');
    }
}