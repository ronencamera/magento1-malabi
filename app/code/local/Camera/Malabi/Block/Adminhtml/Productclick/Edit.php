<?php

class Camera_Malabi_Block_Adminhtml_Productclick_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'productclick';
        $this->_controller = 'adminhtml_productclick';
        
       // $this->_updateButton('save', 'label', Mage::helper('productclick')->__('Save Item'));
        //$this->_updateButton('delete', 'label', Mage::helper('productclick')->__('Delete Item'));
		
		//$this->_updateButton('save', 'label', Mage::helper('adminhtml')->__('Save config'));
        $this->_removeButton('reset');
        $this->_removeButton('back');
		
		

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('productclick_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'productclick_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'productclick_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('productclick_data') && Mage::registry('productclick_data')->getId() ) {
            return Mage::helper('productclick')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('productclick_data')->getTitle()));
        } else {
            return Mage::helper('productclick')->__('Add Item');
        }
    }
}