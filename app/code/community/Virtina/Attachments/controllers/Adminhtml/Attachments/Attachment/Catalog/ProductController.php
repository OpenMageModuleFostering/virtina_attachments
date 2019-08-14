<?php
/**
 * Attachment - product controller
 * 
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");

class Virtina_Attachments_Adminhtml_Attachments_Attachment_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController {
   
    # Constructor
    protected function _construct() {
        # Define module dependent translate
        $this->setUsedModuleName('Virtina_Attachments');
    }

    # Attachments grid in the catalog page
    public function attachmentsGridAction() {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.attachment')
            ->setProductAttachments($this->getRequest()->getPost('product_attachments', null));
        $this->renderLayout();
    }
    
    # Attachments in the catalog page
    public function attachmentsAction() {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.attachment')
            ->setProductAttachments($this->getRequest()->getPost('product_attachments', null));
        $this->renderLayout();
    }   

    
}
