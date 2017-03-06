<?php

class Camera_Malabi_Adminhtml_ProductclickController extends Mage_Adminhtml_Controller_action
{


	protected function _initAction() {
		$this->loadLayout()
		->_setActiveMenu('productclick/items')
		->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

		return $this;
	}

	public function indexAction() {
	$this->_title($this->__('My Form'));
		$this->loadLayout();
		$this->_setActiveMenu('productclick');
		
		$model  = Mage::getModel('productclick/productclick')->load(1);
		Mage::register('productclick_data', $model);
		
		
    $this->_addContent($this->getLayout()->createBlock('productclick/adminhtml_productclick_edit'));
    $this->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('productclick/productclick')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('productclick_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('productclick/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			/* $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('productclick/adminhtml_productclick_edit'))
			->_addLeft($this->getLayout()->createBlock('productclick/adminhtml_productclick_edit_tabs')); */

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productclick')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}

	public function newAction() {
		$this->_forward('edit');
	}

	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {

			$model = Mage::getModel('productclick/productclick');
			$model->setData($data)
			->setId($this->getRequest()->getParam('id'));

			try {

				$model->save();
				$productclick_id = $model->getId();
				if(isset($data['links'])){
					$customers = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['links']['customers']); //Save the array to your database

					$collection = Mage::getModel('productclick/grid')->getCollection();
					$collection->addFieldToFilter('productclick_id',$productclick_id);
					foreach($collection as $obj){
						$obj->delete();
					}
					foreach($customers as $key => $value){
						$model2 = Mage::getModel('productclick/grid');
						$model2->setProductclickId($productclick_id);
						$model2->setCustomerId($key);
						$model2->setPosition($value['position']);
						$model2->save();
					}
				}

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productclick')->__('Your data was successfully saved'));
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
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productclick')->__('Unable to find item to save'));
		$this->_redirect('*/*/');
	}

	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('productclick/productclick');

				$model->setId($this->getRequest()->getParam('id'))
				->delete();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

	public function massDeleteAction() {
		$productclickIds = $this->getRequest()->getParam('productclick');
		if(!is_array($productclickIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
		} else {
			try {
				foreach ($productclickIds as $productclickId) {
					$productclick = Mage::getModel('productclick/productclick')->load($productclickId);
					$productclick->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(
				Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($productclickIds)
				)
				);
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}

}