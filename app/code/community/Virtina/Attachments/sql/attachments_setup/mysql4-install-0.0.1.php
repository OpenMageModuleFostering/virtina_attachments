<?php
/**
 * Attachments module install script
 *
 * Virtina
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
	$installer = $this;
	$installer->startSetup();
	
	$installer->run("
	-- DROP TABLE IF EXISTS {$this->getTable('virtina_attachment')};
	CREATE TABLE IF NOT EXISTS {$this->getTable('virtina_attachment')} (
	  `entity_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Attachment ID',
	  `title` varchar(255) NOT NULL COMMENT 'Title',
	  `uploaded_file` varchar(255) DEFAULT NULL COMMENT 'Uploaded Attachment',
	  `status` smallint(6) DEFAULT NULL COMMENT 'Enabled',
	  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Updated On',
	  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Created On',
	  PRIMARY KEY (`entity_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Attachment Table' AUTO_INCREMENT=1 ;");
	
	$installer->run("
	-- DROP TABLE IF EXISTS {$this->getTable('virtina_attachment_product')};
	CREATE TABLE IF NOT EXISTS {$this->getTable('virtina_attachment_product')} (
	  `ap_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Attachment Product ID',
	  `attachment_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Attachment ID',
	  `product_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Product ID',
	  `position` int(11) NOT NULL DEFAULT '0' COMMENT 'Position',
	  PRIMARY KEY (`ap_id`),
	  UNIQUE KEY `UNQ_VIRTINA_ATTACHMENT_PRD_ATTACHMENT_ID_PRD_ID` (`attachment_id`,`product_id`),
	  KEY `IDX_VIRTINA_ATTACHMENT_PRODUCT_PRODUCT_ID` (`product_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Product Attachment Table' AUTO_INCREMENT=1 ;");    
	 
	$installer->endSetup();    
