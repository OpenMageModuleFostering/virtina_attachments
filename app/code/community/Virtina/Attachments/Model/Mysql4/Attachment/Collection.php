<?php
/**
 * Attachment product model
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
class Virtina_Attachments_Model_Mysql4_Attachment_Collection extends  Mage_Core_Model_Mysql4_Collection_Abstract {

    protected $_joinedFields = array();
    
    public function _construct() {
        parent::_construct();
        $this->_init('attachments/attachment');
    }
    
    
    # Add the product filter to collection
    public function addProductFilter($product) {
    	
        if ($product instanceof Mage_Catalog_Model_Product) {
            $product = $product->getId();
        }
        
        if (!isset($this->_joinedFields['product'])) {
            $this->getSelect()->join(
                array('related_product' => $this->getTable('attachments/attachmentproduct')),
                'related_product.attachment_id = main_table.entity_id',
                array('position')
            );
            
            $this->getSelect()->where('related_product.product_id = ?', $product);
            $this->_joinedFields['product'] = true;
            
        }
        
        return $this;
        
    }    
    
}
