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
    	$post = $this->getRequest()->getParams();
    	if ($post) {

			$productId = 2134;
			$simpleproductid = array('2122', '2123', '2124', '2125', '2126', '2127', '2128', '2129', '2130', '2131', '2132', '2133');
    		$configproduct = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($productId);
			$attributeModel = $this->_objectManager->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute');
			$position = 0;
			$attributes = array(173, 180); // Super Attribute Ids Used To Create Configurable Product
			$associatedProductIds = $simpleproductid;

			foreach ($attributes as $attributeId) {
			    $data = array('attribute_id' => $attributeId, 'product_id' => $productId, 'position' => $position);
			    $position++;
			    $attributeModel->setData($data)->save();
			}
			//$configproduct->setTypeId("configurable"); // Setting Product Type As Configurable
			//$configproduct->setAffectConfigurableProductAttributes(4);
			$this->_objectManager->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable')->setUsedProductAttributeIds($attributes, $configproduct);
			//$configproduct->setNewVariationsAttributeSetId(4); // Setting Attribute Set Id
			$configproduct->setAssociatedProductIds($associatedProductIds);// Setting Associated Products
			$configproduct->setCanSaveConfigurableAttributes(true);
			$configproduct->save();

			exit;

    		$mediaAttribute = array ('thumbnail','small_image','image');
			$mediaDir = $this->_objectManager->get('Magento\Framework\App\Filesystem\DirectoryList')->getPath('media');
			$customerSession = $this->_objectManager->get('Magento\Customer\Model\Session');
			$custId = 1;
			if($customerSession->isLoggedIn()) {
			    $customerSession->getCustomer()->getId();
			}

			$fpath = $mediaDir.'/import/'.$custId;
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

			$csvData = $this->csv->getData($_FILES['uploadcsv']['tmp_name']);
			//new \SimpleXLSX($target_path);
			if ( $xlsx = new \SimpleXLSX($_FILES['uploadcsv']['tmp_name']) ) {
                $sheetData = $xlsx->rows();
            } else {
                echo SimpleXLSX::parse_error();
            }
            
            //echo '<pre>';
            $simpleproductid = array();
            foreach ($sheetData as $row => $data) {
				if ($row > 0) {
					
				 	$producttype = 'simple';
				 	$visiblity = 4;
				 	if ($data[12] == '' || $data[12] == '0') {
				 		$producttype = 'configurable';
				 	}

				 	if (isset($data[22]) || $data[22] == '') {
				 		$visiblity = $data[22];
				 	}

				 	$arrtArr = $multiArr = array();
		         	$catids = explode(',', $data[2]);
		         	$_product = $this->_objectManager->create('Magento\Catalog\Model\Product');
		         	$_product->setWebsiteIds(array(1)); //website ID the product is assigned to, as an array
					$_product->setStoreId(1);
					$_product->setAttributeSetId($data[1]);
					$_product->setTypeId($producttype);
					$_product->setCreatedAt(strtotime('now'));
					$_product->setSku($data[0]);
		            $_product->setName($data[3]);
		            $_product->setStatus(1);
					$_product->setVisibility($visiblity);
					$_product->setWeight($data[9]);
					$_product->setPrice($data[10]);
					$_product->setSpecialPrice($data[11]);
					$_product->setDescription($data[5]);
					$_product->setShortDescription($data[7]);
					$_product->setCategoryIds($catids);
					
					if(isset($data[17]) && $data[17] != '') {
						$_product->setBrand(ucfirst($data[17]));
					}

					if(isset($data[16]) && $data[16] != '') {
						if(strtolower($data[16]) == 'yes')
							$sameday = '219';
						else
							$sameday = '220';
						$_product->setSameDay($sameday);
					}

					if(isset($data[21]) && $data[21] != '') {
						$typeid = explode('-', $data[21]);
						$_product->setWeightCakeType($typeid[0]);
					}

					$_product->setStockData(array(
					        'use_config_manage_stock' => 0, //'Use config settings' checkbox
					        'manage_stock' => 1, //manage stock
					        'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
					        'max_sale_qty' => 2, //Maximum Qty Allowed in Shopping Cart
					        'is_in_stock' => 1, //Stock Availability
					        'qty' => $data[12] //qty
					        )
					    );

					
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
						foreach ($imgArray as $image) {
							$imgUrl = $fpath.'/image/'.$image;
							if (file_exists($imgUrl)) {
								if ($count == 0){
							        $_product->addImageToMediaGallery( $imgUrl , $mediaAttribute, true, false );
							    }else {
							        $_product->addImageToMediaGallery( $imgUrl , null, true, false );
							    }
							    $count++;

							    //unlink($imgUrl);
							}
						}
					}

					$arrtArr['name'] = ($data[4] != '') ? $data[4] : $data[3];
					$arrtArr['description'] = ($data[6] != '') ? $data[6] : $data[5];
					$arrtArr['short_description'] = ($data[8] != '') ? $data[8] : $data[7];
					$arrtArr['brand'] = ($data[18] != '') ? $data[18] : $data[18];
					$multiArr['occasion'] = (isset($data[15]) && $data[15] != '') ? strtolower($data[15]) : '';
					$multiArr['gender'] = (isset($data[19]) && $data[19] != '') ? strtolower($data[19]) : '';
					$multiArr['egg_type'] = (isset($data[20]) && $data[20] != '') ? strtolower($data[20]) : '';
					$multiArr['weight_cake_type'] = (isset($data[21]) && $data[21] != '') ? strtolower($data[21]) : '';

					//Occassion Muliple attribute set
					if ($multiArr['occasion'] != '') {
						$attribute = $this->eavConfig->getAttribute('catalog_product', 'occasion');
						$options = $attribute->getSource()->getAllOptions();
						$occassion = explode(',', $multiArr['occasion']);
						$o = 0;
						$multiOcc = array();
						foreach ($options as $key => $value) {
							if ($key > 0) {
								if (in_array(strtolower($value['label']), $occassion)) {
									$multiOcc[$o] = $value['value'];
									$o++;
								}
							}
						}
						if (!empty($multiOcc))
							$_product->setOccasion($multiOcc);
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
								if (in_array(strtolower($value['label']), $gender)) {
									$multiGen[$o] = $value['value'];
									$o++;
								}
							}
						}
						if (!empty($multiGen))
							$_product->setGender($multiGen);
					}

					//Egg Type Muliple attribute set
					if ($multiArr['egg_type'] != '') {
						$attribute = $this->eavConfig->getAttribute('catalog_product', 'egg_type');
						$options = $attribute->getSource()->getAllOptions();
						$eggtype = explode(',', $multiArr['egg_type']);
						$o = 0;
						$multiEgg = array();
						foreach ($options as $key => $value) {
							if ($key > 0) {
								if (in_array(strtolower($value['label']), $eggtype)) {
									$multiEgg[$o] = $value['value'];
									$o++;
								}
							}
						}
						if (!empty($multiEgg))
							$_product->setEggType($multiEgg);
					}

					//weight_cake_type
					if ($multiArr['weight_cake_type'] != '') {
						$attribute = $this->eavConfig->getAttribute('catalog_product', 'weight_cake_type');
						$options = $attribute->getSource()->getAllOptions();
						$weightype = $multiArr['weight_cake_type'];
						$w = 0;
						$multiWeight = array();
						foreach ($options as $key => $value) {
							if ($key > 0) {
								if (strtolower($value['label']) === strtolower($weightype)) {
									$multiWeight[$w] = $value['value'];
									$w++;
								}
							}
						}
						if (!empty($multiWeight))
							$_product->setWeightCakeType($multiWeight);
					}
					
					$_product->save();
					$productIds[0] = $_product->getId();
					//$simpleproductid[] = $_product->getId();
					$this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Action')->updateAttributes($productIds, $arrtArr, 2);

					if($producttype == 'configurable') {
						$productId = $_product->getId();

						if(isset($data[23]) && $data[23] != '') {
							$skus = explode(',', $data[23]);
							foreach($skus as $sku) {
								if ($sku) {
									$productObj = $this->_objectManager->create('Magento\Catalog\Model\Product')->loadByAttribute('sku', trim($sku));
									if ($productObj) {
										$simpleproductid[] = $productObj->getId();
									}
								}
								
							}
						}

						if (!empty($simpleproductid)) {
							$configproduct = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($productId);
							$attributeModel = $this->_objectManager->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute');
							$position = 0;
							$attributes = array(173, 180); // Super Attribute Ids Used To Create Configurable Product
							$associatedProductIds = $simpleproductid;

							foreach ($attributes as $attributeId) {
							    $data = array('attribute_id' => $attributeId, 'product_id' => $productId, 'position' => $position);
							    $position++;
							    $attributeModel->setData($data)->save();
							}
							$configproduct->setTypeId("configurable"); // Setting Product Type As Configurable
							$configproduct->setAffectConfigurableProductAttributes(4);
							$this->_objectManager->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable')->setUsedProductAttributeIds($attributes, $configproduct);
							$configproduct->setNewVariationsAttributeSetId(4); // Setting Attribute Set Id
							$configproduct->setAssociatedProductIds($associatedProductIds);// Setting Associated Products
							$configproduct->setCanSaveConfigurableAttributes(true);
							$configproduct->save();
						}
					}

					/*$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of Object manager
			        $productId = $_product->getId();
			        $product = $objectManager->create('\Magento\Catalog\Model\Product')->load($productId);
			        $values = [
			            [
			                'record_id'=>0,                                        
			                'title'=>'Red',
			                'price'=>10,
			                'price_type'=>"fixed",
			                'sort_order'=>1,
			                'is_delete'=>0
			            ],
			            [
			                'record_id'=>1,                    
			                'title'=>'White',
			                'price'=>10,
			                'price_type'=>"fixed",
			                'sort_order'=>1,
			                'is_delete'=>0
			            ],
			            [
			                'record_id'=>2,                    
			                'title'=>'Black',
			                'price'=>10,
			                'price_type'=>"fixed",
			                'sort_order'=>1,
			                'is_delete'=>0
			            ]
			        ];
			          
			        $options = [
			            [
			                "sort_order"    => 1,
			                "title"         => "Field Option",
			                "price_type"    => "fixed",
			                "price"         => "",
			                "type"          => "field",
			                "is_require"    => 0
			            ],[
			                "sort_order"    => 2,
			                "title"         => "Color",
			                "price_type"    => "fixed",
			                "price"         => "",
			                "type"          => "drop_down",
			                "is_require"    => 0,
			                "values"        => $values
			            ],[
			                "sort_order"    => 3,
			                "title"         => "Multiple Option",
			                "price_type"    => "fixed",
			                "price"         => "",
			                "type"          => "multiple",
			                "values"        => $values,
			                "is_require"    => 0
			            ]
			        ];
			          
			        $product->setHasOptions(1);
			        $product->setCanSaveCustomOptions(true);
			        foreach ($options as $arrayOption) {
			            $option = $objectManager->create('\Magento\Catalog\Model\Product\Option')
			                    ->setProductId($productId)
			                    ->setStoreId($product->getStoreId())
			                    ->addData($arrayOption);
			            $option->save();
			            $product->addOption($option);
			        }*/
		        }

		    }

		    @rmdir($fpath.'/image');
		    $this->_messageManager->addErrorMessage('Product Added successfully');
    		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        	$resultRedirect->setUrl($this->_redirect('marketplace/index/'));
    	} else {
    		$this->_messageManager->addErrorMessage('Page not found');
    		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        	$resultRedirect->setUrl($this->_redirect('marketplace/index/'));
    	}
    	
        /*$this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();*/
    }
}