<?php

class Camera_Malabi_Model_Observer {


	######  Send email on Product import event
    public function createuser($observer)
    {
	
	  $customer = $observer->getEvent()->getData();
	  $email = $customer['user']->getEmail();
	  $fname = $customer['user']->getFirstname();
	  $lname = $customer['user']->getLastname();
	  
		$model  = Mage::getModel('productclick/productclick')->load(1);
		$userid =  $model->getUserid();
		if(!$userid) { 
				try {
					$datas = array('firstName' => $fname, 
						'lastName' => $lname,
						'userEmail' => $email,
						'userPassword' => 'Abcd1234',
						'customerId' => '405',
						'customerToken' => 'qaq2254a-75b8-40ff-9ef8-2c6ad9cfa13a',
						'acceptsMail' => 'true',
					);
					 
					$url = "https://users.malabi.co/UsersServer/v1/createUser";
					$json = json_encode($datas);
					$client = new Zend_Http_Client($url);
					$response =$client->setRawData($json, null)->request('POST');
					//var_dump($response);
					$result = json_decode($response->getBody (),true);
					$token = $result['user']['userToken'].'<br>';
					$muserid = $result['user']['userId'];
					$status =  $result['status'];
					/* echo '<pre>';
					print_r($result); */
					
					if($status == "success") {					
								$savedata = array('userid' => $muserid, 	'token' => $token,);
								$model = Mage::getModel('productclick/productclick');
								$model->setData($savedata);
								try {
									$model->save();
									Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productclick')->__('Your malabi data was successfully saved'));
								}
								 catch (Exception $e) {
									Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
									}							
					}
					else {
						Mage::getSingleton('adminhtml/session')->addError($result);
					}

					//echo $response->getBody();
				} catch (Exception $ex) {
				Mage::getSingleton('adminhtml/session')->addError($ex->getMessage());
				}
	  
	  }
	  Mage::log($fname, null, 'custom.log');
	  
	  
	}
}
