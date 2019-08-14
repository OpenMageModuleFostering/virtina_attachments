<?php
/**
 * Adminhtml observer
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Model_Adminhtml_Observer {
	
    # Check if tab can be added
    protected function _canAddTab($product) {
        if ($product->getId()) {
            return true;
        }
        
        if (!$product->getAttributeSetId()) {
            return false;
        }
        
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable') {
            if ($request->getParam('attributes')) {
                return true;
            }
        }
        
        return false;
    }
    
    # Save attachment - product relation
    public function saveProductAttachmentInfo($observer) {
        $post = Mage::app()->getRequest()->getPost('attachments', -1);
        
        if ($post != '-1') {
            $post 		= Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product 	= Mage::registry('product');
   	            
				$attachmentProduct = Mage::getModel('attachments/attachmentproduct')->saveProductRelation($product, $post);
				
        }
        
        return $this;
	}

    # Add the attachment tab to products
    public function addProductAttachmentBlock($observer) {
        $block 	= $observer->getEvent()->getBlock();        
        $product	= Mage::registry('product');
        
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab('attachments',
                array(
                  'label' => Mage::helper('attachments')->__('Catalog Attachments'),
                  'url'   => Mage::helper('adminhtml')->getUrl(
						'adminhtml/attachments_attachment_catalog_product/attachments',
						array('_current' => true)
						),
                  'class' => 'ajax',
                )
            );
        }
        
        return $this;
    }
   
}
