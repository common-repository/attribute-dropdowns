<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="attribute-dropdowns-selector" id="attribute_dropdowns_selector_<?php echo $this->getWidgetId(); ?>">
  <div class="block-content">  
    <?php foreach($this->getAttributes() as $attribute): ?>
      <?php echo $this->getAttributeSelectHtml($attribute); ?>
    <?php endforeach; ?>          			
    <button type="button" title="<?php echo __('Search', 'attribute-dropdowns') ?>" class="button attribute-dropdowns-submit"><span><span><?php echo __('Search', 'attribute-dropdowns') ?></span></span></button>            		      	          		      	      
  </div>
</div>
<script>
  jQuery(function($){
    $('#attribute_dropdowns_selector_<?php echo $this->getWidgetId(); ?>').attributeDropdowns({
      submitUrl : "<?php echo $this->getSubmitUrl(); ?>",
      attributeSlugs : <?php echo json_encode($this->getAttributeSlugs()); ?>                              
    });
  });
</script>