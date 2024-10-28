<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pektsekye_AttributeDropdowns_Model_Selector
{  
    
    protected $_wpdb;
                   
        
    public function __construct() {
			global $wpdb;
			
			$this->_wpdb = $wpdb;       
    }	
    
  
  
    public function getAttributeTitles($attributeSlugs)
    {     
      if (count($attributeSlugs) == 0){
        return array();    
      }
                  
      $select = "SELECT attribute_name, attribute_label FROM {$this->_wpdb->base_prefix}woocommerce_attribute_taxonomies WHERE attribute_name IN ('".implode("','", array_map('esc_sql', $attributeSlugs))."') ";
      $rows = (array) $this->_wpdb->get_results($select, ARRAY_A);
      if (count($rows) == 0){
        return array();    
      }
    
      $attribiteTitles = array();
      foreach($rows as $r){
        $attribiteTitles[$r['attribute_name']] = $r['attribute_label'];
      }
    
      return $attribiteTitles;
    }
  
  
    public function getAttributeTermData($attributeSlugs)
    {    
      if (count($attributeSlugs) == 0){
        return array();    
      }
    
      $taxonomiesStr = '';
      foreach ($attributeSlugs as $slug){
        $taxonomiesStr .= ($taxonomiesStr != '' ? ',' : '') ."'pa_".esc_sql($slug)."'";       
      }
     
      $select = "SELECT DISTINCT term_taxonomy.taxonomy, terms.slug, terms.name  
                FROM {$this->_wpdb->posts}
                INNER JOIN {$this->_wpdb->term_relationships} AS term_relationships ON {$this->_wpdb->posts}.ID = term_relationships.object_id
                INNER JOIN {$this->_wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
                INNER JOIN {$this->_wpdb->terms} AS terms USING( term_id )
                WHERE term_taxonomy.taxonomy IN ({$taxonomiesStr})
                ORDER BY terms.name";

      return (array) $this->_wpdb->get_results($select, ARRAY_A);
    }  
		
}
