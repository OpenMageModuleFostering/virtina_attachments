<?php
/**
 * Attachment file field renderer helper
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Block_Adminhtml_Helper_File extends Varien_Data_Form_Element_Abstract {
	
    # Constructor
    public function __construct($data) {
        parent::__construct($data);
        $this->setType('file');
    }

    # Get element HTML 
    public function getElementHtml() {
		
        $html = '';
        $this->addClass('input-file');
        $html .= parent::getElementHtml();
        
        if ($this->getValue()) {
			$att_file_arr = array();
			$att_file_arr = explode("/",$this->getValue());	
			$att_file	  = $att_file_arr[count($att_file_arr)-1];	
			
            $url = $this->_getUrl();
            if (!preg_match("/^http\:\/\/|https\:\/\//", $url)) {
                $url = Mage::helper('attachments/attachment')->getFileBaseUrl() . $url;
            }
            
            $attmodel = Mage::getModel('attachments/attachment');
            
            $ext = $attmodel->getFileExtension($this->getValue(), 1);
            $mediaIcon_path = Mage::getBaseDir('media').'/attachment/icons/' . $ext . '.png';
          
			if (file_exists($mediaIcon_path)) {
				$mediaIcon = Mage::getBaseUrl('media') . '/attachment/icons/' . $ext . '.png';
			} else {
				$mediaIcon = Mage::getBaseUrl('media') . '/attachment/icons/plain.png';
			}
			
			$helper = Mage::helper('attachments/attachment');
			$attachmentPath = Mage::helper('attachments/attachment')->getFileBaseDir().$this->getValue();
			$_fileSize = $helper->getFileSize($attachmentPath);
            
            $html .= '<br /><br /><span class="attach-img"><img src="' . $mediaIcon . '" style="margin-right: 5px;" width="25" /></span>';
            $html .= '<a href="'.$url.'" target="_blank">'.$att_file.'</a>';
            $html .= ' ('.$_fileSize.')';
            
        }
        
        //$html .= $this->_getDeleteCheckbox();
        return $html;
    }

    # Get the delete checkbox HTML
    protected function _getDeleteCheckbox() {
        $html = '';
        
        if ($this->getValue()) {
            $label = Mage::helper('attachments')->__(' Delete File');
            $html .= '<span class="delete-image">';
            $html .= '<input type="checkbox" name="'.
                parent::getName().'[delete]" value="1" class="checkbox" id="'.
                $this->getHtmlId().'_delete"'.($this->getDisabled() ? ' disabled="disabled"': '').'/>';
            $html .= '<label for="'.$this->getHtmlId().'_delete"'.($this->getDisabled() ? ' class="disabled"' : '').'>';
            $html .= $label.'</label>';
            $html .= $this->_getHiddenInput();
            $html .= '</span>';
        }
        
        return $html;
    }
    
    # Get the name
    public function getName() {
        return $this->getData('name');
    }    

    # Get the hidden input
    protected function _getHiddenInput() {
        return '<input type="hidden" name="'.parent::getName().'[value]" value="'.$this->getValue().'" />';
    }

    # Get the file URL
    protected function _getUrl() {
        return $this->getValue();
    }

    
}
