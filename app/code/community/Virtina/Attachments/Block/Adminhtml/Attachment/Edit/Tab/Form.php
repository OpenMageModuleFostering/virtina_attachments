<?php
/**
 * Attachment edit form tab
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Block_Adminhtml_Attachment_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {
    
    # Prepare the form
    protected function _prepareForm() {
		
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('attachment_');
        $form->setFieldNameSuffix('attachment');
        $this->setForm($form);
        
        $fieldset = $form->addFieldset('attachment_form', array('legend' => Mage::helper('attachments')->__('Attachment Info')));        
        $fieldset->addType('file', Mage::getConfig()->getBlockClassName('attachments/adminhtml_helper_file'));
        $fieldset->addField('title', 'text', 
        		array(
            'label' => Mage::helper('attachments')->__('Title'),
            'name'  => 'title',
            'required'  => true,
            'class' => 'required-entry',
           )
        );
		
        $fieldset->addField(
            'uploaded_file', 'file',
            array(
                'label' => Mage::helper('attachments')->__('Uploaded file'),
                'name'  => 'uploaded_file',
				'required'  => $this->getRequest()->getParam('id') ? false:true,
				'class' => $this->getRequest()->getParam('id') ? '':'required-entry',                
           )
        );
        
        $fieldset->addField(
            'status',
            'select',
             array(
                'label'  => Mage::helper('attachments')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('attachments')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('attachments')->__('Disabled'),                    
                    ),
                ),
            )
        );
        
        $formValues = Mage::registry('current_attachment')->getDefaultValues();
        
        if (!is_array($formValues)) {
            $formValues = array();
        }
        
        if (Mage::getSingleton('adminhtml/session')->getAttachmentData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getAttachmentData());
            Mage::getSingleton('adminhtml/session')->setAttachmentData(null);
        }elseif (Mage::registry('current_attachment')) {
            $formValues = array_merge($formValues, Mage::registry('current_attachment')->getData());
        }
        
        $form->setValues($formValues);
        
        return parent::_prepareForm();
        
    }
    
    
}
