<?php
/**
 * Attachment list block
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Block_Attachment_List extends Mage_Core_Block_Template {
	
    # Constructor
    public function __construct() {
        parent::__construct();
        $attachments = Mage::getResourceModel('virtina_attachments/attachment_collection')
                         ->addFieldToFilter('status', 1);
        $attachments->setOrder('title', 'asc');
        $this->setAttachments($attachments);
    }

    # Prepare the layout
    protected function _prepareLayout() {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'virtina_attachments.attachment.html.pager'
        )
        ->setCollection($this->getAttachments());
        $this->setChild('pager', $pager);
        $this->getAttachments()->load();
        return $this;
    }

    # Get the pager HTML
    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }
    
    
}
