<?php
/**
 * Attachment model
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Model_Attachmentproduct extends Mage_Core_Model_Abstract {
	
 
    public function _construct(){
        parent::_construct();
        $this->_init('attachments/attachmentproduct');
    }
    
    # Save  product attachment relations
    public function saveProductRelation($product, $data) {    	
   	
        if (!is_array($data)) {
            $data = array();
        }
        
		  # Delete existing product attachment relation
		  $apmodel = Mage::getModel('attachments/attachmentproduct')->getCollection();
		  $apmodel->addFieldToFilter('product_id', $product->getId());
		  $apmodel->walk('delete');
			
		  # Save product attachment relation					 	
        foreach ($data as $attachmentId => $info) {
        		
        		$info['position']	 =	($info['position'])?$info['position']:0;
        		
        		$model = Mage::getModel('attachments/attachmentproduct');
        		$model->setAttachmentId($attachmentId);
        		$model->setProductId($product->getId());        		
        		$model->setPosition($info['position']);
        		$model->save();
        }
        
        return $this;
        
    }    

    
}
