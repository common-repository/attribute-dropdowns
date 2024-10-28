( function ($) {
  "use strict";

  $.widget("pektsekye.attributeDropdowns", { 


    _create : function () {

      $.extend(this, this.options);       

      this._on({                 
        "click button.attribute-dropdowns-submit": $.proxy(this.submit, this)                                                                       
      });                          
    },
  
  
    submit : function(){
      var slug,value;

      var params = {post_type:'product', ad_search:1};
      
      var hasSelected = false;
      var l = this.attributeSlugs.length;		  
      for (var i=0;i<l;i++){
        slug = this.attributeSlugs[i];
        value = this.element.find('select[name="attribute_dropdowns_select_'+slug+'"]').val();
        if (value){
          params['filter_'+slug] = value;
          hasSelected = true;
        }
      }
      
      if (hasSelected){
        window.location.href = this.submitUrl + '?' + $.param(params);
      }
    }             
    
            
  });
  
})(jQuery);            













