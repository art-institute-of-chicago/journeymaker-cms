(function($) {

  $(document).ready( function() {
    $("#query-api-button").click(function() {

      var url_to_fetch = '/?q=api-query/' + jQuery('#search_type:checked').val()  + '/' + encodeURIComponent(jQuery('#api-text').val());
      
      $.get(url_to_fetch, function(data) {
        $("#api-query-results").html('');
        $("#api-query-results").append(data);
      });

    });

    $("#query-api-button-theme").click(function() {

      var url_to_fetch = '/?q=api-query-theme/' + jQuery('#search_type:checked').val()  + '/' + encodeURIComponent(jQuery('#api-text').val());

      $.get(url_to_fetch, function(data) {
        $("#api-query-results").html('');
        $("#api-query-results").append(data);
      });

    });

    /*-------------------------*/
    if ($('#edit-field-viewing-description-und-0-value').length) {
      var look_again_length = $('#edit-field-viewing-description-und-0-value').val().length;
      $('#field-viewing-description-add-more-wrapper').append('<div id="look-again-chars" style="margin-top:-15px;">Using ' + look_again_length + ' of 125 characters.</div>');

      $('#edit-field-viewing-description-und-0-value').live('keyup blur', function() {
        var maxlength = 125;
        var val = $(this).val();

        if (val.length >= maxlength) {
          $(this).val(val.slice(0, (maxlength-1)));
          $('#look-again-chars').css('color', '#F00').css('font-weight', 'bold');
        } else {
          $('#look-again-chars').css('color', '#000').css('font-weight', 'normal');
        }

        $('#look-again-chars').html('Using ' + val.length + ' of 125 characters.');
      });
    }
    /*------------------------*/

    /*-------------------------*/
    if ($('#edit-field-activity-instructions-und-0-value').length) {
      var activity_length = $('#edit-field-activity-instructions-und-0-value').val().length;
      $('#field-activity-instructions-add-more-wrapper').append('<div id="activity-chars" style="margin-top:-15px;">Using ' + activity_length + ' of 128 characters.</div>');

      $('#edit-field-activity-instructions-und-0-value').live('keyup blur', function() {
        var maxlength = 128;
        var val = $(this).val();

        if (val.length >= maxlength) {
          $(this).val(val.slice(0, (maxlength-1)));
          $('#activity-chars').css('color', '#F00').css('font-weight', 'bold');
        } else {
          $('#activity-chars').css('color', '#000').css('font-weight', 'normal');
        }

        $('#activity-chars').html('Using ' + val.length + ' of 128 characters.');
      });
    }
    /*------------------------*/

    /*-------------------------*/
    if ($('#edit-field-location-directions-und-0-value').length) {
      var location_length = $('#edit-field-location-directions-und-0-value').val().length;
      $('#field-location-directions-add-more-wrapper').append('<div id="location-chars" style="margin-top:-15px;">Using ' + location_length + ' of 145 characters.</div>');

      $('#edit-field-location-directions-und-0-value').live('keyup blur', function() {
        var maxlength = 145;
        var val = $(this).val();

        if (val.length >= maxlength) {
          $(this).val(val.slice(0, (maxlength-1)));
          $('#location-chars').css('color', '#F00').css('font-weight', 'bold');
        } else {
          $('#location-chars').css('color', '#000').css('font-weight', 'normal');
        }

        $('#location-chars').html('Using ' + val.length + ' of 145 characters.');
      });
    }
    /*------------------------*/

    /*-------------------------*/
    if ($('#edit-field-journey-guide-cover-title-und-0-value').length) {
      var journey_length = $('#edit-field-journey-guide-cover-title-und-0-value').val().length;
      $('#field-journey-guide-cover-title-add-more-wrapper').append('<div id="journey-chars" style="margin-top:-15px;">Using ' + journey_length + ' of 25 characters.</div>');

      $('#edit-field-journey-guide-cover-title-und-0-value').live('keyup blur', function() {
        var maxlength = 25;
        var val = $(this).val();

        if (val.length >= maxlength) {
          $(this).val(val.slice(0, (maxlength-1)));
          $('#journey-chars').css('color', '#F00').css('font-weight', 'bold');
        } else {
          $('#journey-chars').css('color', '#000').css('font-weight', 'normal');
        }

        $('#journey-chars').html('Using ' + val.length + ' of 25 characters.');
      });
    }
    /*------------------------*/


  });


})(jQuery);


function populate_artwork_form(id) {

  jQuery("#edit-title").val(jQuery('#title_' + id).data('value'));
  jQuery("#edit-field-object-id-und-0-value").val(jQuery('#id_' + id).data('value'));
  jQuery("#edit-field-image-url-und-0-value").val(jQuery('#image_' + id).data('value'));
  jQuery("#edit-field-artist-und-0-value").val(jQuery('#artist_title_' + id).data('value'));
  jQuery("#edit-field-year-und-0-value").val(jQuery('#date_' + id).data('value'));
  jQuery("#edit-field-gallery-name-und-0-value").val(jQuery('#gallery_' + id).data('value'));
  jQuery("#edit-field-gallery-id-und-0-value").val(jQuery('#gallery_id_' + id).data('value'));
  jQuery("#edit-field-artwork-credit-und-0-value").val(jQuery('#copy_' + id).data('value'));

  jQuery("#edit-field-map-x-und-0-value").val(jQuery('#lat_' + id).data('value'));
  jQuery("#edit-field-map-y-und-0-value").val(jQuery('#lon_' + id).data('value'));
  jQuery("#edit-field-floor-und-0-value").val(jQuery('#floor_' + id).data('value'));

  jQuery('#api-query-results').html('');
}

function populate_theme_form(id) {
  jQuery("#edit-field-background-image-url-und-0-value").val(jQuery('#image_' + id).data('value'));
  jQuery('#api-query-results').html('');
  return false;
}
