<?php 
/**
 * Attachment helper
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Helper_Attachment extends Mage_Core_Helper_Abstract {

    # Get file size
    public function getFileSize($file) {
    	
        $size = filesize($file);
        $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        
        if ($size == 0) {
            return('n/a');
        }else {
            return (round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]);
        }
        
    }

    # Get base files directory
    public function getFileBaseDir() {
        return Mage::getBaseDir('media').DS.'attachment'.DS.'file';
    }
    
    # Get base file URL
    public function getFileBaseUrl() {
        return Mage::getBaseUrl('media').'attachment'.'/'.'file';
    }    
    
    
}
