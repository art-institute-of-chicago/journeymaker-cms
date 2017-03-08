(function($) {
  Drupal.behaviors.nodereference_selector = {
    attach : function(context) {
      $(".field-widget-nodereference-selector input").hide();
      $(".field-widget-nodereference-selector .form-checkboxes label, .field-widget-nodereference-selector .form-radios label").append('<div class="checkmark"></div>');

      $(".field-widget-nodereference-selector .form-item input:checked").next().find('.checkmark').addClass('selected');

      $(".field-widget-nodereference-selector .form-item label").click(function() {
        var container = $(this).closest(".field-widget-nodereference-selector");
        var checkbox = $(this).parent().find("input");
        if(checkbox.attr('checked')) {
          checkbox.attr('checked', false);
        } else {
          checkbox.attr('checked', true);
        }
        container.find('.checkmark').removeClass('selected');
        container.find(':checked').next().find('.checkmark').addClass('selected');
        return false;
      });

      $(".field-widget-nodereference-selector label").eq(0).after('<div><input class="nodereference-selector-filter" type="text" /></div>').parent().find('.nodereference-selector-filter').keyup(function() {
        var search = $(this).val();
        $(this).parent().parent().find('img').parent().show();
        $(this).parent().parent().find('img').filter(function() {
          return $(this).attr("title").toLowerCase().indexOf(search) != 0;
        }).parent().hide();
      });
    }
  }
})(jQuery);
