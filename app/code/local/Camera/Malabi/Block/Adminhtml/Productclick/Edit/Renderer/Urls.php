<?php

class Camera_Malabi_Block_Adminhtml_Productclick_Edit_Renderer_Urls extends Mage_Adminhtml_Block_Widget
 implements Varien_Data_Form_Element_Renderer_Interface
{

 public function __construct()
    {
        $this->setTemplate('malabi/urls.phtml');
    }

    /**
     * Render HTML
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }
	
}

?>