<?php
/**
 * Attachment - product relation edit block
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Block_Adminhtml_Attachment_Edit_Tab_Product extends Mage_Adminhtml_Block_Widget_Grid {
	
    # Constructors
    public function __construct() {
        parent::__construct();
        $this->setId('product_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('desc');
        $this->setUseAjax(true);
        
        if ($this->getAttachment()->getId()) {
            $this->setDefaultFilter(array('in_products'=>1));
        }
    }    

    # Prepare the product collection
    protected function _prepareCollection() {
    	  $admnStore = Mage_Core_Model_App::ADMIN_STORE_ID;
    	  
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->addAttributeToSelect('price');        
        $collection->joinAttribute('product_name', 'catalog_product/name', 'entity_id', null, 'left', $admnStore);
        
        if ($this->getAttachment()->getId()) {
            $constraint = '{{table}}.attachment_id='.$this->getAttachment()->getId();
        }else{
            $constraint = '{{table}}.attachment_id=0';
        }
        
        $collection->joinField('position', 'attachments/attachmentproduct', 'position', 'product_id=entity_id', $constraint, 'left');
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
    	
        $this->addColumn('in_products',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_products',
                'values'=> $this->_getSelectedProducts(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
            
        $this->addColumn('entity_id',
            array(
                'header' => Mage::helper('catalog')->__('ID'),
                'width'  => 50,
                'align'  => 'left',
                'index'  => 'entity_id',
            )
        );
        
        $this->addColumn('product_name',
            array(
                'header'    => Mage::helper('catalog')->__('Name'),
                'align'     => 'left',
                'index'     => 'product_name',
                'renderer'  => 'attachments/adminhtml_catalog_column_renderer_relation',
                'params'    => array('id'    => 'getId'),
                'base_link' => 'adminhtml/catalog_product/edit',
            )
        );
        
        $this->addColumn('sku',
            array(
                'header' => Mage::helper('catalog')->__('SKU'),
                'align'  => 'left',
                'index'  => 'sku',
            )
        );
        
        $this->addColumn('price',
            array(
                'header'        => Mage::helper('catalog')->__('Price'),
                'type'          => 'currency',
                'width'         => '1',
                'currency_code' => (string)Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
                'index'         => 'price'
            )
        );
        
        $this->addColumn('position',
        		array(
                'header'         => Mage::helper('catalog')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
        
    }

    # Retrieve selected products
    protected function _getSelectedProducts() {
        $products = $this->getAttachmentProducts();
        
        if (!is_array($products)) {
            $products = array_keys($this->getSelectedProducts());
        }
        
        return $products;
        
    }

    # Retrieve selected products
    public function getSelectedProducts() {		
		
        $products = array();
       /* $selected = Mage::registry('current_attachment')->getSelectedProducts();
        
        if (!is_array($selected)) {
            $selected = array();
        }
        */
        $attachmentId = $this->getRequest()->getParam('id');
        if(!isset($attachmentId)) {
            $attachmentId = 0;
        }
        
        $collection = Mage::getModel('attachments/attachmentproduct')->getCollection();
		  $collection->addFieldToFilter('attachment_id', $attachmentId);
        
        foreach ($collection as $product) {
            $products[$product->getProductId()] = array('position' => $product->getPosition());
        }
       
        return $products;
        
    }

    # Get row URL
    public function getRowUrl($item) {
        return '#';
    }

    # Get grid URL
    public function getGridUrl() {
        return $this->getUrl('*/*/productsGrid', array('id' => $this->getAttachment()->getId()));
    }

    # Get the current attachment
    public function getAttachment() {
        return Mage::registry('current_attachment');
    }

    # Add filter
    protected function _addColumnFilterToCollection($column) {
    	
        # Set custom filter, in product flag
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            }else{
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
                }
            }
            
        }else{
            parent::_addColumnFilterToCollection($column);
        }
        
        return $this;
        
    }
    
}
