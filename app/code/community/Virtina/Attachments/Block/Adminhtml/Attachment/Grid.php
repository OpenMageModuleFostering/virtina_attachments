<?php
/**
 * Attachment admin grid block
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Block_Adminhtml_Attachment_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	
    # Constructor
    public function __construct(){
        parent::__construct();
        $this->setId('attachmentGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    
    # Prepare collection
    protected function _prepareCollection(){
        $collection = Mage::getModel('attachments/attachment')->getCollection();   
         
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    # Prepare grid collection
    protected function _prepareColumns() {
    	
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('attachments')->__('ID'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        
        $this->addColumn(
            'title',
            array(
                'header'    => Mage::helper('attachments')->__('Title'),
                'align'     => 'left',
                'index'     => 'title',
            )
        );

        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('attachments')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('attachments')->__('Enabled'),
                    '0' => Mage::helper('attachments')->__('Disabled'),
                )
            )
        );
        
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('attachments')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('attachments')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        
        return parent::_prepareColumns();
        
    }

    # Prepare mass action
    protected function _prepareMassaction() {    	
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('attachment');        
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('attachments')->__('Delete Attachment'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('attachments')->__('Do you want to delete the attachment(s)?')
            )
        );
        
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('attachments')->__('Change Status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                     'name'   => 'status',
                     'type'   => 'select',
                     'class'  => 'required-entry',
                     'label'  => Mage::helper('attachments')->__('Status'),
                     'values' => array(
                         '1' => Mage::helper('attachments')->__('Enabled'),
                         '0' => Mage::helper('attachments')->__('Disabled'),
                     )
                    )
                )
            )
        );
        
        return $this;
    }

    # After collection load 
    protected function _afterLoadCollection() {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    # Get the grid URL
    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }      
    
    # Get the row URL
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
  
    
}
