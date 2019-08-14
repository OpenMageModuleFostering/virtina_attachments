<?php
/**
 * Virtina
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Virtina_Attachments_Model_System_Config_Source_Truefalse{

    public function toOptionArray(){
        return array(
		
            array('value' => 'true', 'label'=>Mage::helper('adminhtml')->__('Yes')),
            array('value' => 'false', 'label'=>Mage::helper('adminhtml')->__('No')),  
        );
    }

}
