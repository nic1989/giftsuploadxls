<?php

namespace Gtl\Marketplace\Controller\Index;

use Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\ResultFactory;


class Uploadcsv extends \Magento\Framework\App\Action\Action
{
	protected $csv;
	protected $eavConfig;
	protected $_messageManager;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
	    \Magento\Customer\Model\Session $customerSession,
	    \Magento\Framework\ObjectManagerInterface $objectManager,
	    \Magento\Eav\Model\Config $eavConfig,
    	\Magento\Framework\File\Csv $csv,
    	\Magento\Framework\Message\ManagerInterface $messageManager
	)
	{
		$this->customerSession = $customerSession;
	    $this->_objectManager = $objectManager;
	    $this->eavConfig = $eavConfig;
	    $this->csv = $csv;
	    $this->_messageManager = $messageManager;
	    parent::__construct($context);
	}

    public function execute()
    {
    	require (BP.'/lib/internal/SimpleXlxs/simplexlsx.class.php');
    	ini_set('display_errors', 1);
    	$post = $this->getRequest()->getParams();

    	$productId = 2261;
    	$associatedProductIds = array('2249', '2250', '2251', '2252', '2253', '2254', '2255', '2256', '2257', '2258', '2259', '2260');

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    	$configurable_product = $objectManager->create('\Magento\Catalog\Model\Product');


    	/*try {
	    	
			$associatedProductIds = array('2164', '2165', '2166', '2167', '2168', '2169', '2170', '2171', '2172', '2173', '2174', '2175'); //Product Ids Of Associated Products

			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId); // Load Configurable Product
			$attributeModel = $objectManager->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute');
			$position = 0;
			$attributes = array(182, 180); // Super Attribute Ids Used To Create Configurable Product
			foreach ($attributes as $attributeId) {
			    $data = array('attribute_id' => $attributeId, 'product_id' => $productId, 'position' => $position);
			    $position++;
			    $attributeModel->setData($data)->save();
			}
			$product->setTypeId("configurable"); // Setting Product Type As Configurable
			$product->setAffectConfigurableProductAttributes(4);
			$objectManager->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable')->setUsedProductAttributeIds($attributes, $product);
			$product->setNewVariationsAttributeSetId(4); // Setting Attribute Set Id
			$product->setAssociatedProductIds($associatedProductIds);// Setting Associated Products
			$product->setCanSaveConfigurableAttributes(true);
			$product->save();
    	} catch (\Exception $e) {
			echo $e->getMessage();
    	} */

    	try {
    		$productId = 2277;
			$configurable_product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load(2277);
			
			$configurableProductsData['2266'] = array( //[$simple_product_id] = id of a simple product associated with this configurable
			        '0' => array(
			            'label' => 'Egg Type', //attribute label
			            'attribute_id' => '182', //attribute ID of attribute 'size_general' in my store
			            'value_index' => '232', //value of 'S' index of the attribute 'size_general'
			            'is_percent'    => 0,
			            'pricing_value' => '10',
			        ),
			        '1' => array(
			            'label' => 'Egg Type', //attribute label
			            'attribute_id' => '182', //attribute ID of attribute 'size_general' in my store
			            'value_index' => '233', //value of 'S' index of the attribute 'size_general'
			            'is_percent'    => 0,
			            'pricing_value' => '10',
			        )
			    );
		    //$configurable_product->setId($productId);
		    //$configurable_product->setSku($configurable_product->getSku());
		    $configurable_product->setConfigurableProductsData($configurableProductsData);

			/*$configurable_product->getTypeInstance()->setUsedProductAttributeIds(array(182, 180s), $configurable_product);
		    $configurable_product->setAssociatedProductIds($associatedProductIds);*/ // Setting Associated Products
		    $configurable_product->setCanSaveConfigurableAttributes(true);
		    $configurable_product->save();
	    } catch (\Exception $e) {
			echo $e->getMessage();
    	}
    	exit;

    	/*try {
	    	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	    	$product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId); // Load Configurable Product
	    	$attributeModel = $objectManager->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute');
	    	$position = 0;
	    	$attributes = array(182, 180); // Super Attribute Ids Used To Create Configurable Product
	    	$associatedProductIds = array('2216', '2217', '2218', '2219', '2220', '2221', '2222', '2223', '2224', '2225', '2226', '2227'); //Product Ids Of Associated Products
	    	foreach ($attributes as $attributeId) { 
	    		$data = array('attribute_id' => $attributeId, 'product_id' => $productId, 'position' => $position); 
	    		$position++; 
	    		$attributeModel->setData($data)->save();
	    	}
	    	$product->setTypeId("configurable"); // Setting Product Type As Configurable
	    	$product->setAffectConfigurableProductAttributes(4);
	    	$objectManager->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable')->setUsedProductAttributeIds($attributes, $product);
	    	$product->setNewVariationsAttributeSetId(4); // Setting Attribute Set Id
	    	$product->setAssociatedProductIds($associatedProductIds);// Setting Associated Products
	    	$product->setCanSaveConfigurableAttributes(true);
	    	$product->save();
    	} catch (\Exception $e) {
			echo $e->getMessage();
    	}
    	exit;*/
    	if($post) {

                    $mediaAttribute = array ('thumbnail','small_image','image');
                    $mediaDir = $this->_objectManager->get('Magento\Framework\App\Filesystem\DirectoryList')->getPath('media');
                    $mediaPath = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')
                        ->getStore()
                        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                    $customerSession = $this->_objectManager->get('Magento\Customer\Model\Session');
                    $custId = 1;
                    if($customerSession->isLoggedIn()) {
                        $custId = $customerSession->getCustomer()->getId();
                    }

                    $fpath = $mediaDir.'/import/'.$custId;
                    $furlpath = $mediaPath.'/import/'.$custId;
                    $target_path = '';
                    if (isset($_FILES['imagezip']) && $_FILES['imagezip']['error'] == 0) {
                        if (!file_exists($fpath)) mkdir($fpath, 0777, true);
                        else chmod($fpath, 0777);

                        $target_path = $fpath.'/'.$_FILES['imagezip']['name'];
                        if (@move_uploaded_file($_FILES['imagezip']['tmp_name'], $target_path)) {
                            $zip = new \ZipArchive();
                            $x = $zip->open($target_path);
                            if ($x === true) {
                                $zip->extractTo($fpath); // change this to the correct site path
                                $zip->close();

                                unlink($target_path);
                                unset($_FILES['imagezip']);
                            }
                        }
                    }

                    $xlsx = new \SimpleXLSX($_FILES['uploadcsv']['tmp_name']);
                    if ($xlsx->success())
                        $sheetdata = $xlsx->rows();
                    else
                        echo 'xlsx error: '.$xlsx->error();

                    $i=1;

                    $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
                    $connection = $resource->getConnection();
                    foreach ($sheetdata as $row => $data) {

                        if ($row > 0) {
                            if ($data[0] != '') {
                                $connection->query('SET FOREIGN_KEY_CHECKS=0');

                                $arrtArr = $multiArr = array();
                                $attrsetids = array(16,17,18,19,20,21);
                                if (!in_array($data[1], $attrsetids)) {
                                    $this->messageManager->addError('Attribute Set id should be 16,17,18,19,20 or 21');
                                    return $this->resultRedirectFactory->create()->setPath(
                                        '*/*/uploadcsv',
                                        ['_secure' => $this->getRequest()->isSecure()]
                                    );
                                }

                                $string = preg_replace('/[^A-Za-z0-9\-]/', '', $data[3]);
                                $urlkey = strtolower(str_replace(' ', '-', $string));
                                $productsku = $data[0];
                                $productcollection = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
                                    ->addAttributeToSelect(['url_key'])
                                    ->addAttributeToFilter('url_key', array('eq' => $urlkey));
                                if($productcollection->getSize()) {
                                    $random = substr(str_repeat('abcdefghijklmnopqrstuvwxyz', 6), 0, 3);
                                    $urlkey = $urlkey.'-'.strtolower($productsku).$random.rand(1, 1000);
                                }

                                $productcollectionSku = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
                                    ->addAttributeToFilter('sku', array('eq' => $productsku));
                                if($productcollectionSku->getSize()) {
                                    $random = substr(str_repeat('abcdefghijklmnopqrstuvwxyz123456789', 6), 0, 3);
                                    $productsku = $productsku.'_'.$random;
                                }

                                $producttype = 'simple';
                                $visiblity = 4;
                                if ($data[12] == '' || $data[12] == '0') {
                                    $producttype = 'configurable';
                                }
                                if (isset($data[24]) || $data[24] != '') {
                                    $visiblity = $data[24];
                                }

                                $catids = explode(',', $data[2]);
                                $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of Object manager
                                if ($producttype === 'configurable') {
                                	$configurable_product = $objectManager->create('\Magento\Catalog\Model\Product');

									$configurable_product->setSku($productsku); // set sku
									$configurable_product->setName($data[3]); // set name
									$configurable_product->setAttributeSetId($data[1]);
									$configurable_product->setStatus(1);
									$configurable_product->setTypeId('configurable');
									$configurable_product->setVisibility(4);
									$configurable_product->setPrice(0);
									$configurable_product->setWebsiteIds(array(1)); // set website
									$configurable_product->setCategoryIds(array_map('trim', $catids)); // set category
									$configurable_product->setStockData(array(
										    'use_config_manage_stock' => 0, //'Use config settings' checkbox
										    'manage_stock' => 1, //manage stock
										    'is_in_stock' => 1, //Stock Availability
									    )
									);

									// super attribute 
									$weight_attr_id = $configurable_product->getResource()->getAttribute('weight_cake_type')->getId();
									$egg_attr_id = $configurable_product->getResource()->getAttribute('egg_type')->getId();

									$configurable_product->getTypeInstance()->setUsedProductAttributeIds(array($weight_attr_id, $egg_attr_id), $configurable_product); //attribute ID of attribute 'size_general' in my store

									$configurableAttributesData = $configurable_product->getTypeInstance()->getConfigurableAttributesAsArray($configurable_product);
									$configurable_product->setCanSaveConfigurableAttributes(true);
									$configurable_product->setConfigurableAttributesData($configurableAttributesData);
									$configurableProductsData = array();
									$configurable_product->setConfigurableProductsData($configurableProductsData);
									try {
									    $configurable_product->save();
									} catch (Exception $ex) {
									    $this->messageManager->addError($e->getMessage());

						                return $this->resultRedirectFactory->create()->setPath(
						                    'marketplace/index',
						                    ['_secure' => $this->getRequest()->isSecure()]
						                );
									}

									$lastproductId = $configurable_product->getId();

									$simpleproductid = array();
                                    if(isset($data[25]) && $data[25] != '') {
                                        $skus = explode(',', $data[25]);
                                        foreach($skus as $sku) {
                                            if ($sku) {
                                                $productId = $objectManager->get('Magento\Catalog\Model\Product')->getIdBySku(trim($sku));
                                                if ($productId) {
                                                    $simpleproductid[] = $productId;
                                                }
                                            }
                                        }
                                    }

                                    if (!empty($simpleproductid)) {
                                    	$this->associateProducts($simpleproductid, $lastproductId);
                                    }
                                } else {
                            		$_product = $objectManager->create('Magento\Catalog\Model\Product');
	                                $_product->setWebsiteIds(array(1)); //website ID the product is assigned to, as an array
	                                $_product->setStoreId(0);
	                                $_product->setAttributeSetId($data[1]);
	                                $_product->setTypeId($producttype);
	                                $_product->setCreatedAt(strtotime('now'));
	                                $_product->setUpdatedAt(strtotime('now'));
	                                $_product->setSku($productsku);
	                                //$_product->setHasOptions(1);
	                                $_product->setName($data[3]);
	                                $_product->setUrlKey($urlkey);
	                                $_product->setStatus(1);
	                                $_product->setVisibility($visiblity);
	                                $_product->setWeight($data[9]);
	                                $_product->setPrice($data[10]);
	                                $_product->setSpecialPrice($data[11]);
	                                $_product->setDescription($data[5]);
	                                $_product->setShortDescription($data[7]);
	                                $_product->setCategoryIds(array_map('trim', $catids));

	                                if(isset($data[16]) && $data[16] != '') {
	                                    if(strtolower($data[16]) == 'yes')
	                                        $sameday = '217';
	                                    else
	                                        $sameday = '218';

	                                    $_product->setSameDay($sameday);
	                                }
	                                if (isset($data[20]) && $data[20] != '') {
	                                    $_product->setDeliveryDay($data[20]);
	                                }
	                                if (isset($data[22]) && $data[22] != '') {
	                                    $_product->setTimeDay($data[22]);
	                                }

	                                if(isset($data[23]) && $data[23] != '') {
	                                    $typeid = explode('-', $data[23]);
	                                    $_product->setWeightCakeType($typeid[0]);
	                                }

	                                if(isset($data[19]) && $data[19] != '') {
	                                    if(strtolower($data[19]) == 'egg')
	                                        $eggtype = '232'; //279
	                                    else
	                                        $eggtype = '233'; //280
	                                    $_product->setEggType($eggtype);
	                                }

	                                if ($producttype == 'configurable') {
	                                    $_product->setStockData(
	                                        array(
	                                            'use_config_manage_stock' => 0,
	                                            'manage_stock' => 1, // manage stock
	                                            'is_in_stock' => 1, // Stock Availability of product
	                                        )
	                                    );
	                                } else {
	                                    $_product->setStockData(
	                                        array(
	                                            'use_config_manage_stock' => 0,
	                                            'manage_stock' => 1, // manage stock
	                                            'min_sale_qty' => 1, // Shopping Cart Minimum Qty Allowed
	                                            'max_sale_qty' => 1000, // Shopping Cart Maximum Qty Allowed
	                                            'is_in_stock' => 1, // Stock Availability of product
	                                            'qty' => (int)$data[12]
	                                        )
	                                    );
	                                }

	                                //Create image array for availabe product
	                                $imgArray = array();
	                                if(isset($data[13]) && $data[13] !='') {
	                                    $imgArray[0] = $data[13];
	                                    if(isset($data[14]) && $data[14] !='') {
	                                        $gallArr = explode(',', $data[14]);
	                                        for($g=0; $g<count($gallArr); $g++) {
	                                            array_push($imgArray, $gallArr[$g]);
	                                        }
	                                    }
	                                }

	                                //Assign images to current product
	                                $count = 0;
	                                if (!empty($imgArray)) {
	                                    $g=0;
	                                    foreach ($imgArray as $image) {
	                                        $imgUrl = $fpath.'/image/'.$image;
	                                        $imgPath = $furlpath.'/image/'.$image;
	                                        if (file_exists($imgUrl)) {
	                                            if ($count == 0){
	                                                $_product->addImageToMediaGallery( $imgUrl , $mediaAttribute, false, false );
	                                            } else {
	                                                $_product->addImageToMediaGallery( $imgUrl , null, false, false );
	                                            }
	                                            $count++;
	                                        }
	                                        unset($imgArray[$g]);
	                                        $g++;
	                                    }
	                                }

	                                $arrtArr['name'] = ($data[4] != '') ? $data[4] : $data[3];
	                                $arrtArr['description'] = ($data[6] != '') ? $data[6] : $data[5];
	                                $arrtArr['short_description'] = ($data[8] != '') ? $data[8] : $data[7];
	                                $multiArr['occassion'] = (isset($data[15]) && $data[15] != '') ? strtolower($data[15]) : '';
	                                $multiArr['brand'] = (isset($data[17]) && $data[17] != '') ? strtolower($data[17]) : '';
	                                $multiArr['gender'] = (isset($data[18]) && $data[18] != '') ? strtolower($data[18]) : '';
	                                //$multiArr['egg_type'] = (isset($data[19]) && $data[19] != '') ? strtolower($data[19]) : '';

	                                //Occassion Muliple attribute set
	                                if ($multiArr['occassion'] != '') {
	                                    $attribute = $this->eavConfig->getAttribute('catalog_product', 'occassion');
	                                    $options = $attribute->getSource()->getAllOptions();
	                                    $occassion = explode(',', $multiArr['occassion']);
	                                    $o = 0;
	                                    $multiOcc = array();
	                                    foreach ($options as $key => $value) {
	                                        if ($key > 0) {
	                                            if (in_array(strtolower(trim($value['label'])), array_map('trim', $occassion))) {
	                                                $multiOcc[$o] = $value['value'];
	                                                $o++;
	                                            }
	                                        }
	                                    }

	                                    if (!empty($multiOcc))
	                                        $_product->setOccassion($multiOcc);
	                                }

	                                //Gender Muliple attribute set
	                                if ($multiArr['gender'] != '') {
	                                    $attribute = $this->eavConfig->getAttribute('catalog_product', 'gender');
	                                    $options = $attribute->getSource()->getAllOptions();
	                                    $gender = explode(',', $multiArr['gender']);
	                                    $o = 0;
	                                    $multiGen = array();
	                                    foreach ($options as $key => $value) {
	                                        if ($key > 0) {
	                                            if (in_array(strtolower($value['label']), array_map('trim', $gender))) {
	                                                $multiGen[$o] = $value['value'];
	                                                $o++;
	                                            }
	                                        }
	                                    }
	                                    if (!empty($multiGen))
	                                        $_product->setGender($multiGen);
	                                }

	                                //Brand Single attribute set
	                                if ($multiArr['brand'] != '') {
	                                    $attribute = $this->eavConfig->getAttribute('catalog_product', 'brand');
	                                    $options = $attribute->getSource()->getAllOptions();
	                                    $brand = explode(',', $multiArr['brand']);
	                                    $b = 0;
	                                    $multiBrand = array();
	                                    foreach ($options as $key => $value) {
	                                        if ($key > 0) {
	                                            if (in_array(strtolower($value['label']), array_map('trim', $brand))) {
	                                                $multiBrand[$b] = $value['value'];
	                                                $b++;
	                                            }
	                                        }
	                                    }
	                                    if (!empty($multiBrand))
	                                        $_product->setBrand($multiBrand);
	                                }

	                                try {
	                                	$_product->save();

	                                } catch (\Exception $e) {
						                $this->messageManager->addError($e->getMessage());

						                return $this->resultRedirectFactory->create()->setPath(
						                    'marketplace/index',
						                    ['_secure' => $this->getRequest()->isSecure()]
						                );
						            }


	                                $productIds[0] = $_product->getId();

	                                $updateproduct = $objectManager->create('Magento\Catalog\Model\Product')->load($_product->getId());
	                                $sku = $updateproduct->getSku();

	                                $stockRegistry = $objectManager->create('Magento\CatalogInventory\Api\StockRegistryInterface');
	                                $stockItem = $stockRegistry->getStockItem($updateproduct->getId());
	                                $stockItem->setData('qty', $data[12]);
	                                $stockItem->setData('is_in_stock', 1);
	                                $stockRegistry->updateStockItemBySku($sku, $stockItem);

	                                $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Action')->updateAttributes($productIds, $arrtArr, 2);

	                                $this->_addDeliveryAreacustomOption($_product->getId());
	                                $this->_addDatecustomOption($_product->getId());
	                                $this->_addTimecustomOption($_product->getId());

	                                // Custom query to map product into vendor
	                                /*$tableName = $resource->getTableName('marketplace_product');
	                                //Insert Data into table
	                                $sql = "INSERT INTO " . $tableName . " (`mageproduct_id`, `adminassign`, `seller_id`, `store_id`, `status`, `created_at`, `updated_at`,`is_approved`)
	                                VALUES ('".$_product->getId()."','1','".$custId."','0', '1', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."','1')";
	                                $connection->query($sql);*/
                                }

                               	$connection->query('SET FOREIGN_KEY_CHECKS=1');

                                $i++;
                            }
                        }
                    }

                    $files = glob($fpath.'/image/*'); // get all file names
                    foreach($files as $file){ // iterate files
                        if(is_file($file))
                            unlink($file); // delete file
                    }
                    //rmdir($fpath.'/image');

                    $this->_reindex();

                    $this->messageManager->addSuccess('Product CSV Added successfully');
                    // return $this->resultRedirectFactory->create()->setPath(
                    //     '*/*/productlist',
                    //     ['_secure' => $this->getRequest()->isSecure()]
                    // );
                    return $this->resultRedirectFactory->create()->setPath(
                        'marketplace/index',
                        ['_secure' => $this->getRequest()->isSecure()]
                    );
                } else {

                    $this->_view->loadLayout();
                    $this->_view->renderLayout();
                }
    	
        /*$this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();*/
    }

    public function associateProducts($productids, $lastproductId)
    {
    	$ob = \Magento\Framework\App\ObjectManager::getInstance();
        $configurable_product = $ob->create('Magento\Catalog\Model\Product')->load($lastproductId);
        $configurable_product->setAssociatedProductIds($productids); // Setting Associated Products
        $configurable_product->setCanSaveConfigurableAttributes(true);
        $configurable_product->save();
    }

    public function _addDeliveryAreacustomOption($productId)
    {
        //Custom Options
        $muscatvalues = $this->getMuscatArea();
        $salalahvalues = $this->getSalalahArea();
        $soharvalues = $this->getSoharArea();

        $options = [
            [
                "sort_order"    => 1,
                "title"         => "Select Muscat Area",
                "price_type"    => "fixed",
                "price"         => "",
                "type"          => "drop_down",
                "is_require"    => 0,
                "values"        => $muscatvalues
            ],
            [
                "sort_order"    => 2,
                "title"         => "Select Salalah Area",
                "price_type"    => "fixed",
                "price"         => "",
                "type"          => "drop_down",
                "is_require"    => 0,
                "values"        => $salalahvalues
            ],
            [
            "sort_order"    => 3,
            "title"         => "Select Sohar Area",
            "price_type"    => "fixed",
            "price"         => "",
            "type"          => "drop_down",
            "is_require"    => 0,
            "values"        => $soharvalues
        ]
        ];

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of Object manager
        $productc = $objectManager->create('\Magento\Catalog\Model\Product')->load($productId);
        $productc->unsetOptions();
        $productc->setHasOptions(1);
        $productc->setCanSaveCustomOptions(true);
        $productc->getResource()->save($productc);
        $productc->setOptions(array());
        foreach ($options as $arrayOption) {
            $option = $objectManager->create('\Magento\Catalog\Model\Product\Option')
                ->setProductId($productId)
                ->setStoreId($productc->getStoreId())
                ->addData($arrayOption);
            $option->save();
            $options[] = $option;
            $productc->addOption($option);
        }

        return true;
    }

    public function _addDatecustomOption($productId)
    {
        //Custom Options
        $options = [
            [
                "sort_order"    => 4,
                "title"         => "Delivery Date",
                "price_type"    => "fixed",
                "price"         => "",
                "type"          => "date",
                "is_require"    => 1
            ]
        ];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of Object manager
        $productc = $objectManager->create('\Magento\Catalog\Model\Product')->load($productId);
        $productc->setHasOptions(1);
        $productc->setCanSaveCustomOptions(true);
        //$productc->getResource()->save($productc);
        foreach ($options as $arrayOption) {
            //$productc->setHasOptions(1);
            $option = $objectManager->create('\Magento\Catalog\Model\Product\Option')
                ->setProductId($productId)
                ->setStoreId($productc->getStoreId())
                ->addData($arrayOption);
            $option->save();
            $productc->addOption($option);
        }
        $productc->save();
        
        return true;
        //End Here
    }

    public function _addTimecustomOption($productId)
    {
        //Custom Options
        $values = [
            [
                'title'=>'Morning 10AM - 1PM',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>1,
            ],
            [
                'title'=>'Afternoon 1PM - 4PM',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>2,
            ]
            /*[
                'title'=>'Evening Delivery 4PM - 7PM',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>3,
            ]*/
        ];
        $options = [
            [
                "sort_order"    => 5,
                "title"         => "Delivery Time",
                "price_type"    => "fixed",
                "price"         => "",
                "type"          => "drop_down",
                "is_require"    => 1,
                "values"        => $values
            ]
        ];

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of Object manager
        $productc = $objectManager->create('\Magento\Catalog\Model\Product')->load($productId);
        $productc->unsetOptions();
        $productc->setHasOptions(1);
        $productc->setCanSaveCustomOptions(true);
        $productc->getResource()->save($productc);
        $productc->setOptions(array());
        foreach ($options as $arrayOption) {
            $option = $objectManager->create('\Magento\Catalog\Model\Product\Option')
                ->setProductId($productId)
                ->setStoreId($productc->getStoreId())
                ->addData($arrayOption);
            $option->save();
            $options[] = $option;
            $productc->addOption($option);
        }

        return true;
    }

    public function getMuscatArea()
    {
        $values = [
            [
                'title'=>'Mattrah/Old Muscat',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>1,
            ],
            [
                'title'=>'Ameerat',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>2,
            ],
            [
                'title'=>'Corniche',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>3,
            ],[
                'title'=>'Ruwi',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>4,
            ],[
                'title'=>'CBD Area',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>5,
            ],
            [
                'title'=>'MBD Area',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>6,
            ],
            [
                'title'=>'Darsait',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>7,
            ],
            [
                'title'=>'Wadi Kabir',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>8,
            ],
            [
                'title'=>'Barr al Jissah',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>9,
            ],[
                'title'=>'Bustan',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>10,
            ],[
                'title'=>'Madinat al Ilam',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>11,
            ],
            [
                'title'=>'PDO',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>12,
            ],
            [
                'title'=>'Jibrooh',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>13,
            ],
            [
                'title'=>'Hitab',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>14,
            ],
            [
                'title'=>'Hamriah',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>15,
            ],[
                'title'=>'Wattayah',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>16,
            ],[
                'title'=>'Qurum',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>17,
            ],
            [
                'title'=>'Mina Al Fahal',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>18,
            ],
            [
                'title'=>'Shati Qurum',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>19,
            ],
            [
                'title'=>'Madinat Sultan Qaboos – MSQ',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>20,
            ],
            [
                'title'=>'Al Khuwair',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>21,
            ],[
                'title'=>'Al Khuwair 33',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>22,
            ],[
                'title'=>'Bousher',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>23,
            ],
            [
                'title'=>'Ghala',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>24,
            ],
            [
                'title'=>'Ghala Industrial',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>25,
            ],
            [
                'title'=>'AL Ghoubrah',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>26,
            ],
            [
                'title'=>'Azaiba',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>27,
            ],
            [
                'title'=>'Seeb',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>28,
            ],
            [
                'title'=>'Al Khoud',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>29,
            ],[
                'title'=>'Mawellah',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>30,
            ],[
                'title'=>'Mabella',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>31,
            ],
            [
                'title'=>'Russayl',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>32,
            ],
        ];

        return $values;
    }

    public function getSalalahArea()
    {
        $values = [
            [
                'title'=>'23 July Street - Salalah',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>1,
            ],
            [
                'title'=>'Al Dahreez North',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>2,
            ],
            [
                'title'=>'Al Dahreez South',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>3,
            ],[
                'title'=>'Al Hafa',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>4,
            ],[
                'title'=>'Al Hasila',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>5,
            ],
            [
                'title'=>'Al Karath',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>6,
            ],
            [
                'title'=>'Al Qard',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>7,
            ],
            [
                'title'=>'Al Quaff',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>8,
            ],
            [
                'title'=>'Al Sada Middle',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>9,
            ],[
                'title'=>'Al Sada North',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>10,
            ],[
                'title'=>'Al Sada South',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>11,
            ],
            [
                'title'=>'Al Salam Street',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>12,
            ],
            [
                'title'=>'Al Wadi',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>13,
            ],
            [
                'title'=>'Awqad North',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>14,
            ],
            [
                'title'=>'Awqad South',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>15,
            ],[
                'title'=>'Darbat',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>16,
            ],[
                'title'=>'Dhofar Club Salalah',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>17,
            ],
            [
                'title'=>'Gamaa Zofar (New)',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>18,
            ],
            [
                'title'=>'Gamaa Zofar',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>19,
            ],
            [
                'title'=>'KM Trading',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>20,
            ],
            [
                'title'=>'Martaza',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>21,
            ],[
                'title'=>'Mina Salalah',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>22,
            ],[
                'title'=>'New Salalah',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>23,
            ],
            [
                'title'=>'Omantel Kadim',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>24,
            ],
            [
                'title'=>'Rayssot',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>25,
            ],
            [
                'title'=>'Salalah Garden Mall',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>26,
            ],
            [
                'title'=>'Salalah 5',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>27,
            ],
            [
                'title'=>'Salalah Airport (New)',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>28,
            ],
            [
                'title'=>'Salalah Airport',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>29,
            ],[
                'title'=>'Salalah Gharbiya',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>30,
            ],[
                'title'=>'Salalah Industrial',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>31,
            ],
            [
                'title'=>'Salalah Sharqyia',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>32,
            ],
            [
                'title'=>'Salalah Wosta',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>33,
            ],
        ];

        return $values;
    }

    public function getSoharArea()
    {
        $values = [
            [
                'title'=>'Afifa',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>1,
            ],
            [
                'title'=>'Al Awaynat',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>2,
            ],
            [
                'title'=>'Al Ghafra',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>3,
            ],[
                'title'=>'Al Ghushbah',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>4,
            ],[
                'title'=>'Al Hadhirah',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>5,
            ],
            [
                'title'=>'Al Hambar',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>6,
            ],
            [
                'title'=>'Al Multaqa',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>7,
            ],
            [
                'title'=>'Al Tareef',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>8,
            ],
            [
                'title'=>'Al Waqiba 5',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>9,
            ],[
                'title'=>'Al Waqiba',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>10,
            ],[
                'title'=>'Al Zafraan',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>11,
            ],
            [
                'title'=>'AMQ',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>12,
            ],
            [
                'title'=>'Falaj Al Awhi',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>13,
            ],
            [
                'title'=>'Falaj Al Qabail',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>14,
            ],
            [
                'title'=>'Ghayl Al Shabul',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>15,
            ],[
                'title'=>'Haraat Al Shaikh',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>16,
            ],[
                'title'=>'Hogra',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>17,
            ],
            [
                'title'=>'Karwan',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>18,
            ],
            [
                'title'=>'Khour Al Siyabi',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>19,
            ],
            [
                'title'=>'Khuwayriyah',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>20,
            ],
            [
                'title'=>'Magas Al Khubra',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>21,
            ],[
                'title'=>'Magas Al Soghra',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>22,
            ],[
                'title'=>'Majan',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>23,
            ],
            [
                'title'=>'Majees',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>24,
            ],
            [
                'title'=>'Mina Sohar (Sohar Port)',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>25,
            ],
            [
                'title'=>'Outeb',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>26,
            ],
            [
                'title'=>'Sallan',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>27,
            ],
            [
                'title'=>'Sanggar',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>28,
            ],
            [
                'title'=>'Al Khoud',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>29,
            ],[
                'title'=>'Sohar Sanaiyah',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>30,
            ],[
                'title'=>'Sohar University',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>31,
            ],
            [
                'title'=>'Suwayhirah',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>32,
            ],
            [
                'title'=>'Wadi Hibi',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>33,
            ],
            [
                'title'=>'Saraa Khadeem',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>34,
            ],
            [
                'title'=>'Saraa',
                'price'=>'',
                'price_type'=>"fixed",
                'sort_order'=>35,
            ]
        ];

        return $values;
    }

    public function _reindex()
    {
        $indexerFactory = $this->_objectManager->get('Magento\Indexer\Model\IndexerFactory');
        $indexerIds = array(
            'catalog_category_product',
            'catalog_product_category',
            'catalog_product_price',
            'catalog_product_attribute',
            'cataloginventory_stock',
            'catalogrule_product',
            'catalogsearch_fulltext',
        );
        foreach ($indexerIds as $indexerId) {
            //echo " create index: ".$indexerId."\n";
            $indexer = $indexerFactory->create();
            $indexer->load($indexerId);
            $indexer->reindexAll();
        }

        return true;
    }
}