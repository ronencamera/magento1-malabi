<?php

class Camera_Malabi_ProductController extends Mage_Core_Controller_Front_Action
{



    public function createuserAction(){



        try {
            $datas = array('firstName' => 'John',
                'lastName' => 'Smith',
                'userEmail' => rand(1,222).'_test@camera51.com',
                'userPassword' => 'Abcd1234',
                'customerId' => '405',
                'customerToken' => 'qaq2254a-75b8-40ff-9ef8-2c6ad9cfa13a',
                'acceptsMail' => 'true',
            );
//$url = "https://users.malabi.co/UsersServer/v1/retrieveUserData";
            $url = "https://users.malabi.co/UsersServer/v1/createUser";
            $json = json_encode($datas);
            $client = new Zend_Http_Client($url);
            $client->setHeaders('Content-type','application/json');

           // var_dump($json);
            $response =$client->setRawData($json, null)->request('POST');
            //var_dump($response);
            $result = json_decode($response->getBody (),true);
            echo '<pre>';
            print_r($result);



            exit;


            echo $result->userToken;

            echo $result;

//echo $response->getBody();
        } catch (Exception $ex) {
            echo $ex;
        }


    }

    public function addimageAction()
    {

        if ($data = $this->getRequest()->getParam('product_id')) {
            try {
                $product = Mage::getModel('catalog/product')->load($this->getRequest()->getParam('product_id'));
                $trackId = $this->getRequest()->getParam('trackid');

                try {
                    $imgPath = '/tmp/malabi_' . $trackId . '.jpeg';

                    if (!file_exists($imgPath)) {
                        echo "The file $filename not found";
                        exit;
                    }
                    $product->addImageToMediaGallery($imgPath, 'media_image', false, false);
                    $product->save();
//                    var_dump(__LINE__,$product->getMediaGalleryImages());exit;
                } catch (Exception $e) {
                    var_dump(__LINE__, $e);
                    exit;
                }
                echo "done"; exit;


            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());


            }
            exit;
        }
    }


    public function indexAction() {

        if ($data = $this->getRequest()->getParam('product_id')) {

            $model  = Mage::getModel('productclick/productclick')->load(1);
            $userid =  $model->getUserid();
            $token =  $model->getToken();
            if(empty($userId) || empty($token)){
                echo json_encode(
                    [
                        'status' => 'fail',
                        'subscription' => 'malabi-user-not-set',
                        'message' => 'could not retrieve info, check your Malabi Account'
                    ]
                );
                exit;

            }


            try {
                $product = Mage::getModel('catalog/product')->load($this->getRequest()->getParam('product_id'));

                $imageId = $this->getRequest()->getParam('image_id');
                $imageFile = null;
                $imageUrl = null;
                $imgInfo = null;
                $trackImageAddition = null;

                if(empty($imageId)){
                    $a = urldecode($_SERVER['QUERY_STRING']);
                    $pieces = explode("image_file=", $a);
                    $pieces = explode("&", $pieces[1]);
                    $imageUrl = $pieces[0];
                    $trackImageAddition =  "_". md5($imageUrl);

                } else{
                    foreach ($product->getMediaGalleryImages() as $image){
                        //
                        if($imageId == $image['value_id']){
                            $imgInfo = $image;
                            $imageFile = $imgInfo["file"];
                            $imageUrl = $imgInfo["url"];
                            $trackImageAddition =  "_". $imageId;
                            continue;
                        }
                    }
                    if(empty($image["url"])){
                        return "false";
                    }

                }

                $parseUrl = parse_url($imageUrl);

                $originalUrl = $parseUrl['scheme'] . '://' .  $parseUrl['host'].  $parseUrl['path'];//
                if(!empty($parseUrl['query'])){
                    $originalUrl .= '?' . $parseUrl['query'];
                }

                /*
                 *
                 * From DB get the image object


                $token = retrieve from module configuration
                $userId = retrieve from module configuration
                */

                if(empty($product->getTrackid())){

                        $trackId = $this->generateRandomString() ;
                        $product->setTrackid($trackId);
                        $product->save();
                } else {
                    $trackId = $product->getTrackid();
                }



                //
                $imageTrackId = $trackId.$trackImageAddition;
                $token = 'user_87af282e57704f50b90909ba40ba016936c17b5b8e584223b19b22432cc95212';
                $userId = '3669';
                // end EXAMPLE

                //check subscription
                if($this->checkSubscription($userId, $token ) == 0){
                    echo json_encode(
                        [
                            'status' => 'fail',
                            'subscription' => 'notactive'
                        ]
                    );
                    exit;
                }
                if($this->checkSubscription($userId, $token ) == -1){
                    echo json_encode(
                        [
                            'status' => 'fail',
                            'subscription' => 'not-available',
                            'message' => 'could not retrieve info, check your Malabi Account'
                        ]
                    );
                    exit;
                }


                try {
                    $result = $this->getImage(
                        $originalUrl,
                        $imageTrackId,
                        $userId,
                        $token

                    );
                } catch (Exception $e){
                    var_dump($e->getMessage());
                }

                try {
                 //   var_dump($result);exit;
                    copy("http:".$result['resultImageURL'], '/tmp/malabi_'.$imageTrackId.'.jpeg');

                } catch (Exception $e){
                    var_dump($e->getMessage());
                }

                echo json_encode(
                    [
                        'malabiFileLocation' => '/tmp/malabi_'.$imageTrackId.'.jpeg',
                        'resultImageURL' => $result['resultImageURL'],
                        'trackId' => $imageTrackId
                    ]
                );
//                echo $result;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);

            }
        }
        exit;
    }

    private function checkSubscription($userId, $token){

        $datas = array(
            'userId' => $userId,
            'userToken' => $token,
        );
        $url = "https://users.malabi.co/UsersServer/v1/getUserCredit";
        $json = json_encode($datas);

        $client = new Zend_Http_Client($url);
        $client->setHeaders('Content-type','application/json');
        $response =$client->setRawData($json, null)->request('POST');

        $responseDataRaw = json_decode($response->getBody (),true);
        if(isset($responseDataRaw['status']) && $responseDataRaw['status'] == "fail") {
           return -1;
        }

        if(isset($responseDataRaw['status']) && $responseDataRaw['status'] == "success") {
            if(isset($responseDataRaw['userCredit']) && $responseDataRaw['userCredit'] >0 ) {
                return 1;
            } else {
                return 0;
            }
        }

        return -1;

    }



    private function getImage($originalImage, $trackId, $userId, $token){

        // check if userID and token exsits.
        $result = null;
        $datas = array(
            'originalImageURL' => $originalImage,
            'trackId'           => $trackId,
            'userId' => $userId,
            'token' => $token,
            'shadow' => 'true',
            'transparent' => 'false',
            'forceResultImage' => 'true'
        );
        $url = "https://api.malabi.co/Camera51Server/processImage";
        $json = json_encode($datas);

        $client = new Zend_Http_Client($url);
        $client->setParameterPost($datas);
        $client->request('POST');
        $result = null;
        $responseDataRaw = json_decode($client->request()->getBody(), true);



        $responseData = isset($responseDataRaw['response']) ? $responseDataRaw['response'] : null;


        if (isset($responseData['resultImageURL']) && $responseData['resultImageURL'] && isset($responseData['processingResultCode']) && $responseData['processingResultCode'] < 100) {
            //var_dump($responseData);
            return $responseData;
        }

        return $result;

    }

    private function generateRandomString($length = 22) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            try {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            } catch (Exception $e){
                var_dump($e);
            }

        }
        return $randomString;
    }
}