<?php
/**
 * Attachment admin edit tabs
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Block_Adminhtml_Attachment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {
   
    # Constructors
    public function __construct() {
        parent::__construct();
        $this->setId('attachment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('attachments')->__('Attachment Details'));
    }

    # Before render HTML
    protected function _beforeToHtml() {
    	
        $this->addTab(
            'form_attachment',
            array(
                'label'   => Mage::helper('attachments')->__('Attachment Info'),
                'title'   => Mage::helper('attachments')->__('Attachment Info'),
                'content' => $this->getLayout()->createBlock('attachments/adminhtml_attachment_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        
        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('attachments')->__('Associated Products'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        
        return parent::_beforeToHtml();
        
    }

    # Retrieve attachment
    public function getAttachment() {
        return Mage::registry('current_attachment');
    }
    
    
}
