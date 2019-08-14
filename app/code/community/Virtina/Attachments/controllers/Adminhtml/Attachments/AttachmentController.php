<?php
/**
 * Attachment admin controller
 *
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Adminhtml_Attachments_AttachmentController extends Mage_Adminhtml_Controller_Action {
    
    # Init attachment
    protected function _initAttachment(){
    	
        $attachmentId		= (int) $this->getRequest()->getParam('id');
        $attachment			= Mage::getModel('attachments/attachment');
        
        if ($attachmentId) {
            $attachment->load($attachmentId);
        }
        
        Mage::register('current_attachment', $attachment);
        
        return $attachment;
        
    }

    # Index action 
    public function indexAction(){
        $this->loadLayout();
        $this->_title(Mage::helper('attachments')->__('Catalog Attachments'));
        $this->renderLayout();
    }

    # Grid action
    public function gridAction() {
        $this->loadLayout()->renderLayout();
    }
    
    # New action
    public function newAction() {
        $this->_forward('edit');
    }
    
    # Edit action
    public function editAction() {
        $attachmentId    = $this->getRequest()->getParam('id');
        $attachment      = $this->_initAttachment();
        
        if ($attachmentId && !$attachment->getId()) {
        	
            $this->_getSession()->addError(
                Mage::helper('attachments')->__('This attachment no longer exists!!!')
            );
            
            $this->_redirect('*/*/');
            return;
        }
        
        $data = Mage::getSingleton('adminhtml/session')->getAttachmentData(true);
        
        if (!empty($data)) {
            $attachment->setData($data);
        }
        
        Mage::register('attachment_data', $attachment);
        
        $this->loadLayout();
        $this->_title(Mage::helper('attachments')->__('Virtina'))
             ->_title(Mage::helper('attachments')->__('Attachments'));
             
        if ($attachment->getId()) {
            $this->_title($attachment->getTitle());
        }else {
            $this->_title(Mage::helper('attachments')->__('Add attachment'));
        }
        
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        
        $this->renderLayout();
        
    }

    # Save action
    public function saveAction() {
    	
        if ($data = $this->getRequest()->getPost('attachment')) {
			$allowedFormats	=	array();
			$allowedFormats	=	strtolower(Mage::getStoreConfig('virtina_attachments/attachment/attachallowed'));
			$allowedFormats	=	explode(",",$allowedFormats);			
    	        	
            try {
                $attachment = $this->_initAttachment();
                $attachment->addData($data);

                if($_FILES['uploaded_file']['name']){
					$allowedFileSize	=	$_FILES['uploaded_file']['size'];
					
					# Check if allowed file type
					$att_file_arr = array();
					$att_file_arr = explode(".",$_FILES['uploaded_file']['name']);	
					$att_file	  = strtolower($att_file_arr[count($att_file_arr)-1]);	
					
					if(!in_array($att_file, $allowedFormats)){
						
					 	#throw new Exception('Such a file type is allowed to upload!!!');

						if (isset($data['uploaded_file']['value'])) {
							$data['uploaded_file'] = $data['uploaded_file']['value'];
						}
						
						Mage::getSingleton('adminhtml/session')->addError(
							Mage::helper('attachments')->__('Such a file type is allowed to upload!!!')
						);
						Mage::getSingleton('adminhtml/session')->setAttachmentData($data);
						$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
						
						return;						 	
					}				
								
					$uploadedFileName = $this->_uploadAndGetName(
						'uploaded_file',
						Mage::helper('attachments/attachment')->getFileBaseDir(),
						$data
					);
					
					$attachment->setData('uploaded_file', $uploadedFileName);
                }
                
                $attachment->save();
                
                $attachmentId =  $attachment->getId();
                $products = $this->getRequest()->getPost('products', -1);
					
                if ($products != -1) {        
						# Delete existing
						$apmodel = Mage::getModel('attachments/attachmentproduct')->getCollection();
						$apmodel->addFieldToFilter('attachment_id', $attachmentId);
						$apmodel->walk('delete');
					 
					 	$productIds = Mage::helper('adminhtml/js')->decodeGridSerializedInput($products);      
					 	foreach($productIds as  $key =>$productId){
							$model = Mage::getModel('attachments/attachmentproduct');
							$model->setAttachmentId($attachmentId);
							$model->setProductId($key);
							$model->save();
					 	}
					}
                
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('attachments')->__('Attachment is successfully saved')
                );
                
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $attachment->getId()));
                    return;
                }
                
                $this->_redirect('*/*/');
                return;
                
            } catch (Mage_Core_Exception $e) {
            	
                if (isset($data['uploaded_file']['value'])) {
                    $data['uploaded_file'] = $data['uploaded_file']['value'];
                }
                
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setAttachmentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                
                return;
                
            } catch (Exception $e) {
            	
               // Mage::logException($e);
                
                if (isset($data['uploaded_file']['value'])) {
                    $data['uploaded_file'] = $data['uploaded_file']['value'];
                }
                
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('attachments')->__('There is a problem saving the attachment!!!')
                );
                
                Mage::getSingleton('adminhtml/session')->setAttachmentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                
                return;
                
            }
        }
        
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('attachments')->__('Unable to find attachment to save!!!')
        );
        
        $this->_redirect('*/*/');
    }

    # Delete action
    public function deleteAction() {
    	
        if ( $this->getRequest()->getParam('id') > 0) {
        	
            try {
                $attachment = Mage::getModel('attachments/attachment');
                $attachment->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('attachments')->__('Attachment is successfully deleted.')
                );
                
                $this->_redirect('*/*/');
                return;
                
            } catch (Mage_Core_Exception $e) {
            	
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                
            } catch (Exception $e) {
            	
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('attachments')->__('There is an error deleting attachment!!!')
                );
                
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                
                return;
                
            }
            
        }
        
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('attachments')->__('Could not find attachment to delete!!!')
        );
        
        $this->_redirect('*/*/');
    }

    # Mass delete action
    public function massDeleteAction() {
    	
        $attachmentIds = $this->getRequest()->getParam('attachment');
        
        if (!is_array($attachmentIds)) {
        	
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('attachments')->__('Please select attachment(s) to delete!!!')
            );
            
        }else {
        	
            try {
                foreach ($attachmentIds as $attachmentId) {
                    $attachment = Mage::getModel('attachments/attachment');
                    $attachment->setId($attachmentId)->delete();
                }
                
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('attachments')->__('Total of %d attachments are successfully deleted.', count($attachmentIds))
                );
                
            } catch (Mage_Core_Exception $e) {
            	
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                
            } catch (Exception $e) {
            	
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('attachments')->__('There is an error deleting attachment(s)!!!')
                );
                
                Mage::logException($e);
                
            }
            
        }
        
        $this->_redirect('*/*/index');
        
    }

    # Mass status change action
    public function massStatusAction() {
    	
        $attachmentIds = $this->getRequest()->getParam('attachment');
        
        if (!is_array($attachmentIds)) {
        	
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('attachments')->__('Please select attachment(s)!!!')
            );
            
        }else {
        	
            try {
            	
                foreach ($attachmentIds as $attachmentId) {
                	
	                $attachment = Mage::getSingleton('attachments/attachment')->load($attachmentId)
	                            ->setStatus($this->getRequest()->getParam('status'))
	                            ->setIsMassupdate(true)
	                            ->save();
                }
                
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d attachments are successfully updated.', count($attachmentIds))
                );
                
            } catch (Mage_Core_Exception $e) {
            	
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                
            } catch (Exception $e) {
            	
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('attachments')->__('There is an error updating attachment(s)!!!')
                );
                
                Mage::logException($e);
                
            }
        }
        
        $this->_redirect('*/*/index');
    }

    # Get grid of products action
    public function productsAction() {   
        $this->_initAttachment();
        $this->loadLayout();
        $this->getLayout()->getBlock('attachment.edit.tab.product')
            ->setAttachmentProducts($this->getRequest()->getPost('attachment_products', null));
        $this->renderLayout();
    }

    # Get grid of products action
    public function productsgridAction() {
        $this->_initAttachment();
        $this->loadLayout();
        $this->getLayout()->getBlock('attachment.edit.tab.product')
            ->setAttachmentProducts($this->getRequest()->getPost('attachment_products', null));
        $this->renderLayout();
    }
    
    # Upload file and get the name
    protected function _uploadAndGetName($input, $destinationFolder, $data) {
    	
        try {
        	
            if (isset($data[$input]['delete'])) {
                return '';
            }else {
                $uploader = new Varien_File_Uploader($input);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $uploader->setAllowCreateFolders(true);
                $result = $uploader->save($destinationFolder);
                return $result['file'];
            }
            
        } catch (Exception $e) {
        	
            if ($e->getCode() != Varien_File_Uploader::TMP_NAME_EMPTY) {
                throw $e;
            }else {
                if (isset($data[$input]['value'])) {
                    return $data[$input]['value'];
                }
            }
            
        }
        
        return '';
        
    }    

 	protected function _isAllowed(){
		return true;
	}		
		       
    
}
