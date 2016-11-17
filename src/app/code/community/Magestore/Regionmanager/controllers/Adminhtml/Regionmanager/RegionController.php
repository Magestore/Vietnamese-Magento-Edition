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
class Magestore_Regionmanager_Adminhtml_Regionmanager_RegionController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Magestore_Regionmanager_Adminhtml_RegionmanagerController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('regionmanager/region')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Regions Manager'),
                Mage::helper('adminhtml')->__('Region Manager')
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
        $regionmanagerId     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('regionmanager/region')->load($regionmanagerId);

        if ($model->getId() || $regionmanagerId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('region_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('regionmanager/region');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Region Manager'),
                Mage::helper('adminhtml')->__('Region Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Region News'),
                Mage::helper('adminhtml')->__('Region News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('regionmanager/adminhtml_region_edit'))
                ->_addLeft($this->getLayout()->createBlock('regionmanager/adminhtml_region_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('regionmanager')->__('Region does not exist')
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
              
            $model = Mage::getModel('regionmanager/region');        
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            
            try {
                $model->save();
				
				$name = $data['default_name'];
				$locales = Mage::helper('regionmanager')->getLocales();
					$resource = Mage::getSingleton('core/resource');
					$write = $resource->getConnection('core_write');
					$regionName = $resource->getTableName('directory/country_region_name');
					$write->delete($regionName, array('region_id =' . $model->getRegionId()));
					foreach ($locales as $locale) {
						$localeName = $data['name_' . $locale];
						if ($localeName == '' && $locale == 'en_US') {
							$localeName = $name;
						}
						if ($localeName) {
							$write->insert($regionName, array('region_id' => $model->getRegionId(), 'locale' => $locale, 'name' => trim($localeName)));
					}
				}
				
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('regionmanager')->__('Region was successfully saved')
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
                $model = Mage::getModel('regionmanager/region');
                $model->setId($this->getRequest()->getParam('id'));
				$resource = Mage::getSingleton('core/resource');
				$write = $resource->getConnection('core_write');
				$regionName = $resource->getTableName('directory/country_region_name');
				$write->delete($regionName, array('region_id =' . $model->getRegionId()));
				
               $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Region was successfully deleted')
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
        $regionmanagerIds = $this->getRequest()->getParam('region');
        if (!is_array($regionmanagerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Region(s)'));
        } else {
            try {
                foreach ($regionmanagerIds as $regionmanagerId) {
                    $regionmanager = Mage::getModel('regionmanager/region')->load($regionmanagerId);
					
					$resource = Mage::getSingleton('core/resource');
					$write = $resource->getConnection('core_write');
					$regionName = $resource->getTableName('directory/country_region_name');
					$write->delete($regionName, array('region_id =' . $regionmanager->getRegionId()));
					
					$regionmanager->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d Region(s) were successfully deleted',
                    count($regionmanagerIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function importAction() {
        $this->loadLayout()
                ->_setActiveMenu('regionmanager/region');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('regionmanager/adminhtml_region_import'));
        $this->_title($this->__('Region Manager'))
                ->_title($this->__('Import Region'));
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
                    $regionData = array();
					$count = 0;
                    foreach ($dataFile as $row => $cols) {
                        if ($row == 0) {
                            $fields = $cols;
                        } else {
							$array = array_combine($fields, $cols);
							if(isset($array['country_id']) && isset($array['code']) && isset($array['default_name']))
								$regionData[] = $array;
                        }
						if(count($regionData) > 1000){
							Mage::getResourceModel('regionmanager/region')->importRegionFromCsv($regionData);
							$count += count($regionData);
							$regionData = array();
						}
                    }
					if(count($regionData)){
							Mage::getResourceModel('regionmanager/region')->importRegionFromCsv($regionData);
							$count += count($regionData);
					}
                }
				
                if (isset($count) && $count) {
                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Imported total %d region(s)', $count));
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
    public function downloadSampleAction() {
        $filename = Mage::getBaseDir('media') . DS . 'regionmanager' . DS . 'import_region_vn_sample.csv';
        $this->_prepareDownloadResponse('import_region_vn_sample.csv', file_get_contents($filename));
    }

    /**
     * export grid item to CSV type
     */
    public function exportCsvAction()
    {
        $fileName   = 'regionmanager.csv';
        $content    = $this->getLayout()
                           ->createBlock('regionmanager/adminhtml_region_export')
                           ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName   = 'regionmanager.xml';
        $content    = $this->getLayout()
                           ->createBlock('regionmanager/adminhtml_region_export')
                           ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('regionmanager/region');
    }
}