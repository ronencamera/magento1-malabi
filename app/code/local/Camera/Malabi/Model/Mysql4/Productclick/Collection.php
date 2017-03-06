<?php

class Camera_Malabi_Model_Mysql4_Productclick_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('productclick/productclick');
    }
}