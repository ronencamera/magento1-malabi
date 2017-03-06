<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('customer_productclick')};
CREATE TABLE {$this->getTable('customer_productclick')} (
  `productclick_id` int(11) unsigned NOT NULL auto_increment,
  `userid` varchar(255) NOT NULL default '',
  `token` varchar(255) NOT NULL default '',
  PRIMARY KEY (`productclick_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$setup->addAttribute('catalog_product', 'trackid', array(
    'source'        => '',
    'group'         => 'Extra Label',
    'label'         => 'Track Id',
    'input'         => 'text',
    'class'         => '',
    'global'        => true,
    'visible'       => true,
    'required'      => false,
    'user_defined'  => false,
    'visible_on_front' => false,
    'sort_order'	=> 35,
));

$setup->addAttribute('catalog_product', 'backgroundstatus', array(
    'source'        => '',
    'group'         => 'Extra Label',
    'label'         => 'Background Status',
    'input'         => 'text',
    'class'         => '',
    'global'        => true,
    'visible'       => true,
    'required'      => false,
    'user_defined'  => false,
    'visible_on_front' => false,
    'sort_order'	=> 35,
));

$installer->endSetup();