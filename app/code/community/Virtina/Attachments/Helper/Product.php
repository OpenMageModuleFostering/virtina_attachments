<?php
/**
 * Product helper
 * 
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Helper_Product extends Virtina_Attachments_Helper_Data {

    # Get the selected attachments for a catalog
    public function getSelectedAttachments(Mage_Catalog_Model_Product $product) {
    	
        if (!$product->hasSelectedAttachments()) {
            $attachments = array();
            
            foreach ($this->getSelectedAttachmentsCollection($product) as $attachment) {
                $attachments[] = $attachment;
            }
            
            $product->setSelectedAttachments($attachments);
            
        }
        
        return $product->getData('selected_attachments');
        
    }

    # Get attachment collection for a catalog
    public function getSelectedAttachmentsCollection(Mage_Catalog_Model_Product $product) {
           	
        $collection = Mage::getResourceSingleton('attachments/attachment_collection')
            ->addProductFilter($product);
            
        return $collection;
        
    }
    
}
