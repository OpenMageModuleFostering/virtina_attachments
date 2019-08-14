<?php
/**
 * related entities column renderer
 * 
 * @package    Virtina_Attachments
 * @copyright  Copyright (coffee) 2015-2016 Virtina. (http://www.virtina.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtina_Attachments_Block_Adminhtml_catalog_Column_Renderer_Relation extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text {
    
    # Render the column
    public function render(Varien_Object $row) {
    	
        $base = $this->getColumn()->getBaseLink();
        
        if (!$base) {
            return parent::render($row);
        }
        
        $paramsData = $this->getColumn()->getData('params');
        $params = array();
        
        if (is_array($paramsData)) {
            foreach ($paramsData as $name=>$getter) {
                if (is_callable(array($row, $getter))) {
                    $params[$name] = call_user_func(array($row, $getter));
                }
            }
        }
        
        $staticParamsData = $this->getColumn()->getData('static');
        
        if (is_array($staticParamsData)) {
            foreach ($staticParamsData as $key=>$value) {
                $params[$key] = $value;
            }
        }
        
        return '<a href="'.$this->getUrl($base, $params).'" target="_blank">'.$this->_getValue($row).'</a>';
        
    }
    
}
