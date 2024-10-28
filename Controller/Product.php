<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pektsekye_AttributeDropdowns_Controller_Product {


	public function __construct() {  		  
    add_filter('woocommerce_page_title', array($this, 'page_title' ));									  				
	}


  public function page_title($title) {
    global $pagenow;

    if ('index.php' == $pagenow && isset($_GET['ad_search'])) { 
 
      $attributeSlugs = array();
      $values = array();      
      foreach($_GET as $param => $value){
        if (strpos($param, 'filter_') === 0){
          $slug = str_replace('filter_', '', $param);
          $attributeSlugs[] = $slug;
          $values[$slug] = sanitize_text_field(stripslashes($value));
        }  
      }
      
      include_once( Pektsekye_AD()->getPluginPath() . 'Model/Selector.php');		
      $selector =  new Pektsekye_AttributeDropdowns_Model_Selector();
      $attributeTitles = $selector->getAttributeTitles($attributeSlugs);      
      
      $valuesStr = '';
      foreach($attributeSlugs as $slug){
        if (isset($attributeTitles[$slug]))
          $valuesStr .= ($valuesStr == '' ? '' : ', ') . $attributeTitles[$slug].' '.$values[$slug];  
      }      
      $title = sprintf(__('Showing results for: %s', 'attribute-dropdowns'), $valuesStr);
    }
    
    return $title;		
  }


}
