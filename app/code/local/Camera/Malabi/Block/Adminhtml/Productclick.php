<?php
class Camera_Malabi_Block_Adminhtml_Productclick extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_productclick';
    $this->_blockGroup = 'productclick';
    $this->_headerText = Mage::helper('productclick')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('productclick')->__('Add Item');
    parent::__construct();
  }
}