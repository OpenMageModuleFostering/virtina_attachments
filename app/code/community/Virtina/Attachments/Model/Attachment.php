<?php
/**
 * Attachment model
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Model_Attachment extends Mage_Core_Model_Abstract {
 
    public function _construct(){
        parent::_construct();
        $this->_init('attachments/attachment');
    }

    # Get attachment path
    public function getAttachmentPath(){
        return Mage::helper('attachments/attachment')->getFileBaseDir() . $this->getData('uploaded_file');
    }  
    
    # Get attachment extension
    public function getFileExtension($filename, $pos = 0) {
        return strtolower(substr($filename, strrpos($filename, '.') + $pos));
    }

    # Get attachment icon
    public function getIcon() {
	 	$attachmentPath = $this->getData('uploaded_file');
	 	$ext = $this->getFileExtension($attachmentPath, 1);
	 	
	 	$mediaIcon_path = Mage::getBaseDir('media').'/attachment/icons/' . $ext . '.png';
 	
		if (file_exists($mediaIcon_path)) {
			$mediaIcon = Mage::getBaseUrl('media') . '/attachment/icons/' . $ext . '.png';
		} else {
			$mediaIcon = Mage::getBaseUrl('media') . '/attachment/icons/plain.png';
		}	 
		     
	 	$html = '<span class="attach-img"><img src="' . $mediaIcon . '" alt="View/Download File" style="margin-right: 5px;" width="40px"/></span>';
	 	return $html;
    }  
    
    # Get attachment URL
    public function getAttachmentUrl() {
        return Mage::helper('attachments/attachment')->getFileBaseUrl() . $this->getUploadedFile();
    }
           
        
}
