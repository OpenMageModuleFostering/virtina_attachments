<?php
/**
 * Attachment admin edit form
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Block_Adminhtml_Attachment_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {
	
    # Constructor
    public function __construct() {
        parent::__construct();
        $this->_blockGroup = 'attachments';
        $this->_controller = 'adminhtml_attachment';
        
        $this->_updateButton('save', 'label', Mage::helper('attachments')->__('Save') );        
        $this->_updateButton('delete', 'label', Mage::helper('attachments')->__('Delete Attachment') );
        $this->_addButton('saveandcontinue',
            array(
                'label'   => Mage::helper('attachments')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -200
        );
        
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";        
    }

    # Get the edit form header 
    public function getHeaderText() {
    	
        if (Mage::registry('current_attachment') && Mage::registry('current_attachment')->getId()) {
        	
            return Mage::helper('attachments')->__("Edit Attachment '%s'", $this->escapeHtml(Mage::registry('current_attachment')->getTitle()));    
                    
        } else {
            return Mage::helper('attachments')->__('Add Attachment');
        }
        
    }    
    
}
