<?php

class Camera_Malabi_Block_Adminhtml_Productclick_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form(array(
                                      'id' => 'edit_form',
                                      'action' => $this->getUrl('*/*/save', array('id' => 1)),
                                      'method' => 'post',
        							  'enctype' => 'multipart/form-data'
                                   )
      );

      $form->setUseContainer(true);
      $this->setForm($form);
	  
	   $fieldset = $form->addFieldset('productclick_form', array('legend'=>Mage::helper('productclick')->__('Your token')));
     
      $fieldset->addField('userid', 'text', array(
          'label'     => Mage::helper('productclick')->__('User Id'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'userid',
      ));
	  
	 	  
	  $cssadd = $fieldset->addField('token', 'text', array(
          'label'     => Mage::helper('productclick')->__('Token'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'token',
      ));
	  
	$fieldset->addField('save', 'submit', array(
	'name'      => 'save',
    'value' => 'Submit',
	'class' => 'submit_button'
));

	  
	  $cssadd->setAfterElementHtml("<style>   .entry-edit {  width: 38%; background: #fff;   margin: auto; -moz-box-shadow:    3px 3px 5px 6px #ccc;
  -webkit-box-shadow: 3px 3px 5px 6px #ccc;
  box-shadow:         3px 3px 5px 6px #ccc; padding: 50px;     min-height: 250px; } 
.entry-edit .entry-edit-head {  padding: 2px 10px; background: #fff; border-bottom:1px solid #d6d6d6; }

.entry-edit .entry-edit-head h4 { color:#333 !important; font-size: 2em;   line-height: 36px; }

.box, .entry-edit fieldset, .entry-edit .fieldset { border: none !important;   background: #fff !important;}
.content-header { display:none; }
.submit_button { background-color: #e7e7e7; color: black;
    border: 1px solid #d6d6d6;
      cursor: pointer;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;}
	
.submit_button:hover { background-color: #f4f5f5; }

.form-list td.value input.input-text, .form-list td.value textarea { width: 220px; padding: 5px;}
#messages { width: 46%;   margin: auto; }
</style>
      <p>
      <a href='http://www.google.com'>cancel</a></p>"
      );
     
      if ( Mage::getSingleton('adminhtml/session')->getProductclickData() )
      {
          $form->addValues(Mage::getSingleton('adminhtml/session')->getProductclickData());
          Mage::getSingleton('adminhtml/session')->setProductclickData(null);
      } elseif ( Mage::registry('productclick_data') ) {
          $form->addValues(Mage::registry('productclick_data')->getData());
      }
	  
      return parent::_prepareForm();
  }
}