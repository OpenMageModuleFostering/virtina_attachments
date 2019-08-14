<?php
/**
 * Attachments default helper
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Helper_Data extends Mage_Core_Helper_Abstract{
	
    # Convert array to options
    public function convertOptions($options) {
    	
        $converted = array();
        
        foreach ($options as $option) {
        	
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
            
        }
        
        return $converted;
        
    }  
    
    public function getAttachHeadFromConfig() {
		$attachhead = Mage::getStoreConfig('virtina_attachments/attachment/attachhead');
		//$attachhead = ($attachhead == '')? $this->__('Catalog Attachments'): $attachhead;
        return $attachhead;
    }   

    public function getTabTitleFromConfig() {
		$tabheading = Mage::getStoreConfig('virtina_attachments/attachment/tabheading');
		$tabheading = ($tabheading == '')? $this->__('Attachments'): $tabheading;
        return $tabheading;
    }       
    
}
