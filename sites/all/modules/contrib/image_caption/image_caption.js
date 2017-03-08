(function($) {
  
Drupal.behaviors.image_caption = {
  attach: function (context, settings) {
    $("img.caption:not(.caption-processed)").each(function(i) {
      var imgwidth = $(this).width() ? $(this).width() : false;
      var imgheight = $(this).height() ? $(this).height() : false;
      
      // Get caption from title attribute
      var captiontext = $(this).attr('title');
      
      // Get image alignment and style to apply to container
      if($(this).attr('align')){
        var alignment = $(this).attr('align');
        $(this).css({'float':alignment}); // add to css float
        $(this).removeAttr('align');
      }else if($(this).css('float')){
        var alignment = $(this).css('float');
      }else{
        var alignment = 'normal';
      }
      var style = $(this).attr('style') ? $(this).attr('style') : '';

      // Reset img styles as are added to container instead      
      $(this).removeAttr('width');
      $(this).removeAttr('height');
      $(this).css('width', '');
      $(this).css('height', '');     
      $(this).removeAttr('align');
      $(this).removeAttr('style');
      
      //Display inline block so it doesn't break any text aligns on the parent contatiner
      $(this).wrap("<span class=\"image-caption-container\" style=\"display:inline-block;" + style + "\"></span>");
      $(this).parent().addClass('image-caption-container-' + alignment);
      
      // Add dimensions, if available
      if(imgwidth){
        $(this).width(imgwidth);
        $(this).parent().width(imgwidth);
      }
      if(imgheight){
        $(this).height(imgheight);
      }
      // Append caption
      $(this).parent().append("<span style=\"display:block;\" class=\"image-caption\">" + captiontext + "</span>");
      
      // Add class to prevent duplicate caption adding
      $(this).addClass('caption-processed');
    });
  }
};

})(jQuery);
