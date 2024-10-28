<?php
/**
 * Plugin Name: Attribute Dropdowns
 * Description: Displays multiple product attributes as a search box with drop-down selects. So that customer can search for products by attributes.
 * Version: 1.0.0
 * Author: Pektsekye
 * Author URI: http://hottons.com
 * License: GPLv2     
 * Requires at least: 4.7
 * Tested up to: 6.4.3
 *
 * Text Domain: attribute-dropdowns
 *
 * WC requires at least: 3.0
 * WC tested up to: 8.6.1
 * 
 * @package AttributeDropdowns
 * @author Pektsekye
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


final class Pektsekye_AttributeDropdowns {

  protected static $_instance = null;

  protected $_pluginUrl; 
  protected $_pluginPath;    

  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
      self::$_instance->initApp();
    }
    return self::$_instance;
  }


  public function __construct() {

    $this->_pluginPath = plugin_dir_path( __FILE__ );
    $this->_pluginUrl  = plugins_url('/', __FILE__ );
  }


  public function initApp() {
    $this->includes();
    $this->init_hooks();
    $this->init_controllers();    
  }
  
  
  public function includes() {      
    include_once( 'Widget/Selector.php' );         
  }
  

  private function init_hooks() {      
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts') );
     
    add_action( 'widgets_init', array( $this, 'register_widgets') );    
    add_shortcode( 'attribute_dropdowns_selector', array( $this, 'show_selector_by_shortcode' ) );  		     	                 	  
  }    


  private function init_controllers() {
		if ($this->is_request('frontend')){
      include_once( 'Controller/Product.php' );
      $controller = new Pektsekye_AttributeDropdowns_Controller_Product();    	
    }              	                 	  
  }
  

  public function enqueue_frontend_scripts() {
    wp_enqueue_script('attribute_dropdowns_script', $this->_pluginUrl . 'view/frontend/web/main.js', array('jquery', 'jquery-ui-widget'));    
    wp_enqueue_style('attribute_dropdowns_style', $this->_pluginUrl . 'view/frontend/web/main.css' );	      	  		  			
  }
  
  
  public function register_widgets() {
    register_widget('Pektsekye_AttributeDropdowns_Widget_Selector');         
  }
  

  public function show_selector_by_shortcode($atts) {
  
    include_once($this->getPluginPath() . 'Block/Selector.php');
          
    $block = new Pektsekye_AttributeDropdowns_Block_Selector();
    $block->setWidgetId('content');
 
    if (isset($atts['attribute_slugs'])){
      $attributeSlugsStr = str_replace(' ', '', strip_tags($atts['attribute_slugs']));
      $block->setAttributeSlugsStr($attributeSlugsStr);      
    }            
           
    ob_start();

    $block->toHtml();

    $contents = ob_get_clean();
    
    return $contents;
  }    


  private function is_request( $type ) {
    switch ( $type ) {
      case 'admin' :
        return is_admin();
      case 'ajax' :
        return defined( 'DOING_AJAX' );
      case 'cron' :
        return defined( 'DOING_CRON' );
      case 'frontend' :
        return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
    }
  }
  
  
  public function getPluginUrl() {
    return $this->_pluginUrl;
  }
  
  
  public function getPluginPath() {
    return $this->_pluginPath;
  } 
    
}


function Pektsekye_AD() {
	return Pektsekye_AttributeDropdowns::instance();
}

// If WooCommerce plugin is installed and active.
if (in_array('woocommerce/woocommerce.php', (array) get_option('active_plugins', array())) || in_array('woocommerce/woocommerce.php', array_keys((array) get_site_option('active_sitewide_plugins', array())))){
  Pektsekye_AD();
}

