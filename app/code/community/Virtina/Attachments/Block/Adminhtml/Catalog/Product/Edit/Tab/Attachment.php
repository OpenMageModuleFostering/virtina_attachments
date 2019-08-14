<?php
/**
 * Attachment tab on product edit form
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Block_Adminhtml_Catalog_Product_Edit_Tab_Attachment extends Mage_Adminhtml_Block_Widget_Grid {
    
    # Constructor
    public function __construct() {
    	
        parent::__construct();
        $this->setId('attachment_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('desc');
        $this->setUseAjax(true);
        
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_attachments'=>1));
        }
        
    }

    # Prepare the attachment collection
    protected function _prepareCollection() {
    	
        $collection = Mage::getResourceModel('attachments/attachment_collection');
        
        if($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        }else{
            $constraint = 'related.product_id=0';
        }
        
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('attachments/attachmentproduct')),
            'related.attachment_id=main_table.entity_id AND '.$constraint,
            array('position')
        );
        
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
        
    }

    # Prepare mass action grid
    protected function _prepareMassaction() {
        return $this;
    }

    # Prepare the grid columns
    protected function _prepareColumns() {
    	
        $this->addColumn('in_attachments',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_attachments',
                'values'=> $this->_getSelectedAttachments(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );

        $this->addColumn('entity_id',
            array(
                'header' => Mage::helper('catalog')->__('ID'),
                'width'  => '1',
                'align'  => 'left',
                'index'  => 'entity_id',
            )
        );

        $this->addColumn('title',
            array(
                'header' => Mage::helper('attachments')->__('Title'),
                'align'  => 'left',
                'index'  => 'title',
                'renderer' => 'attachments/adminhtml_catalog_column_renderer_relation',
                'params' => array(
                'id' => 'getId'
                ),
                'base_link' => 'adminhtml/attachments_attachment/edit',
            )
        );
        
        $this->addColumn('position',
            array(
                'header'         => Mage::helper('attachments')->__('Position'),
                'name'           => 'position',
                'width'          => 55,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
        
        return parent::_prepareColumns();
    }

    # Retrieve selected attachments
    protected function _getSelectedAttachments() {
        $attachments = $this->getProductAttachments();
        
        if (!is_array($attachments)) {
            $attachments = array_keys($this->getSelectedAttachments());
        }
        
        return $attachments;
    }

    # Retrieve selected attachments
    public function getSelectedAttachments() {
        $attachments = array();
        
        # used helper here in order not to override the product model
        $selected = Mage::helper('attachments/product')->getSelectedAttachments(Mage::registry('current_product'));
        
        if (!is_array($selected)) {
            $selected = array();
        }
        
        foreach ($selected as $attachment) {
            $attachments[$attachment->getId()] = array('position' => $attachment->getPosition());
        }
        
        return $attachments;
    }
    
    # Add filter
    protected function _addColumnFilterToCollection($column) {
        if ($column->getId() == 'in_attachments') {
            $attachmentIds = $this->_getSelectedAttachments();
            
            if (empty($attachmentIds)) {
                $attachmentIds = 0;
            }
            
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$attachmentIds));
            }else {
            	
                if ($attachmentIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$attachmentIds));
                }
                
            }
            
        }else {
            parent::_addColumnFilterToCollection($column);
        }
        
        return $this;
        
    }   
    
    # Get the current product
    public function getProduct() {
        return Mage::registry('current_product');
	}
	
    # Get row URL
    public function getRowUrl($item) {
        return '#';
    }

    # Get grid URL
    public function getGridUrl() {
        return $this->getUrl('*/*/attachmentsGrid', array('id'=>$this->getProduct()->getId()));
    }

    
}
