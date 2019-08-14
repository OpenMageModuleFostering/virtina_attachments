<?php
/**
 * Attachment admin block
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Block_Adminhtml_Attachment extends Mage_Adminhtml_Block_Widget_Grid_Container {
   
    # Constructor
    public function __construct() {
        $this->_controller         = 'adminhtml_attachment';
        $this->_blockGroup         = 'attachments';
        parent::__construct();
        $this->_headerText         = Mage::helper('attachments')->__('Manage Attachments');
        $this->_updateButton('add', 'label', Mage::helper('attachments')->__('Add Attachment'));
     }
    
}
