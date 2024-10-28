<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pektsekye_AttributeDropdowns_Block_Selector {

  protected $_adSelector;
  
  protected $_widgetId = '';  
  protected $_attributeSlugs = array();  
    
    
	public function __construct() {
    include_once( Pektsekye_AD()->getPluginPath() . 'Model/Selector.php');		
    $this->_adSelector = new Pektsekye_AttributeDropdowns_Model_Selector();			   			
	}
  
     
  public function setWidgetId($id){
    $this->_widgetId = $id;
  } 
 
    
  public function getWidgetId(){
    return $this->_widgetId;
  } 
 
           
  public function setAttributeSlugsStr($attributeSlugsStr){
    if ($attributeSlugsStr != ''){
      $this->_attributeSlugs = array_map('trim', explode(',', $attributeSlugsStr));      
    }
  } 
 
    
  public function getAttributeSlugs(){
    return $this->_attributeSlugs;
  }  
  
  
  public function getAttributes()
  {         

    $attributesSlugs = $this->getAttributeSlugs();

    $attribiteTitles = $this->_adSelector->getAttributeTitles($attributesSlugs);

    $rows = $this->_adSelector->getAttributeTermData($attributesSlugs);
    if (count($rows) == 0){
      return array();    
    }
      
    $attribiteTerms = array();
    foreach($rows as $r){
      $attribiteTerms[$r['taxonomy']][] = array('label' => $r['name'], 'value' => $r['slug']);
    }    
  
    $attributes = array();
  
    foreach($attributesSlugs as $slug){
      if (!isset($attribiteTitles[$slug])) {
        continue;
      }  
    
      $taxonomy = 'pa_'.$slug;
        
      $attributes[] = array(
        'slug' => $slug,
        'title' => $attribiteTitles[$slug],      
        'options' => $attribiteTerms[$taxonomy]
      );  
    }
  
    return $attributes;    
  } 
 
 
   public function getAttributeSelectHtml($attribute)         
  {
    $defaultValue = ' -- ' . $attribute['title'] . ' -- '; 
         
    $selectedTermSlug = isset($_GET['filter_'.$attribute['slug']]) ? sanitize_text_field(stripslashes($_GET['filter_'.$attribute['slug']])) : '';
       
    $html = '<select class="attribute-dropdowns-select" name="attribute_dropdowns_select_'.esc_attr($attribute['slug']).'"><option value="">'.esc_html($defaultValue).'</option>';			      
    foreach($attribute['options'] as $option){
      $html .= '<option value="'.esc_attr($option['value']).'" '.($option['value'] == $selectedTermSlug ? 'selected="selected"': '').'>'.esc_html($option['label']).'</option>';			
    }
    $html .= '</select>';

    return $html;    
  }


   public function getSubmitUrl()     
  {   
    return home_url( '/' );
  }  
   
   
   public function toHtml()
  {    
    include(Pektsekye_AD()->getPluginPath() . 'view/frontend/templates/selector.php');
  }


}
