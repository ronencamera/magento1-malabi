<?php

class Camera_Malabi_Model_Productclick extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('productclick/productclick');
    }
}