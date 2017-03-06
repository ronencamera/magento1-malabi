<?php

class Camera_Malabi_Model_Mysql4_Productclick extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {           
        $this->_init('productclick/productclick', 'productclick_id');
    }
}