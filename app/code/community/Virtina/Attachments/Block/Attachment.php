<?php
/**
 * Attachment list on product page block
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Block_Attachment extends Mage_Catalog_Block_Product_Abstract {
    
    # Get the list of attachments
    public function getAttachmentCollection() {

        if (!$this->hasData('attachment_collection')) {
            $product = Mage::registry('product');
            $collection = Mage::getResourceSingleton('attachments/attachment_collection')
                ->addFieldToFilter('status', 1)
                ->addProductFilter($product);
            $collection->getSelect()->order('related_product.position', 'ASC');
            $this->setData('attachment_collection', $collection);
        }
        
        return $this->getData('attachment_collection');
        
    }

}
