<?php
/**
 * Attachment edit form
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Block_Adminhtml_Attachment_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {
	
    # Prepare form
    protected function _prepareForm() {
    	
        $form = new Varien_Data_Form(
            array(
                'id'         => 'edit_form',
                'action'     => $this->getUrl(
                    '*/*/save',
                    array(
                        'id' => $this->getRequest()->getParam('id')
                    )
                ),
                'method'     => 'post',
                'enctype'    => 'multipart/form-data'
            )
        );
        
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
        
    }
    
}
