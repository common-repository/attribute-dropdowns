<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pektsekye_AttributeDropdowns_Widget_Selector extends WP_Widget {


	function __construct() {

		$widgetOptions = array(
			'description' => __( 'Attribute search box for WooCommerce products.', 'attribute-dropdowns')
		);

		parent::__construct(false, _x('Attribute Dropdowns', 'Wgdet title in admin panel', 'attribute-dropdowns'), $widgetOptions);
	}


	// widget form creation
	function form($instance) {	

    // Check values
    if ( $instance && !empty($instance['title'])) {
      $title = esc_attr($instance['title']);
    } else {
      $title = __('Search by Attribute', 'attribute-dropdowns');
    }
    if ( $instance && !empty($instance['attribute_slugs'])) {
      $attributeSlugs = esc_attr($instance['attribute_slugs']);
    } else {
      $attributeSlugs = 'tyre-width, tyre-profile, tyre-rim';
    }    

    ?>
      <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Search Box Title', 'attribute-dropdowns'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
      </p>
      <p>
      <label for="<?php echo $this->get_field_id('attribute_slugs'); ?>"><?php echo __('Attribute slugs separated with comma ,', 'attribute-dropdowns'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('attribute_slugs'); ?>" name="<?php echo $this->get_field_name('attribute_slugs'); ?>" type="text" value="<?php echo $attributeSlugs; ?>" />
      </p>                    
    <?php
	}


	// widget update
	function update($new_instance, $old_instance) {
    $instance = $old_instance;
    // Fields
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['attribute_slugs'] = strip_tags($new_instance['attribute_slugs']);                
    return $instance;
	}


	// widget display
	function widget($args, $instance) {
    extract( $args );

    $title = isset($instance['title']) ? $instance['title'] : '';
    $attributeSlugsStr = isset($instance['attribute_slugs']) ? $instance['attribute_slugs'] : '';

    echo $before_widget;
    // Display the widget

    echo '<div class="widget-text wp_widget_plugin_box">';

    // Check if title is set
    if ( $title ) {
      echo $before_title . $title . $after_title;
    }

    include_once( Pektsekye_AD()->getPluginPath() . 'Block/Selector.php'); 
         
    $block = new Pektsekye_AttributeDropdowns_Block_Selector();
    if (isset($args['id'])){
      $block->setWidgetId($args['id']);
    }
    $block->setAttributeSlugsStr($attributeSlugsStr);             
    $block->toHtml();

    echo '</div>';
    echo $after_widget;
	}
}