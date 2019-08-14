<?php
/**
 * Attachment product model
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
class Virtina_Attachments_Model_Mysql4_Attachmentproduct_Collection extends  Mage_Core_Model_Mysql4_Collection_Abstract {

   public function _construct(){
        parent::_construct();
        $this->_init('attachments/attachmentproduct');
    }
    
}
