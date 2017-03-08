jQuery(document).ready(function($) {
    var change_flag = false;
    check_userkey();
    //ugly fix for AHAH (add more button can't have same name) @ http://drupal.org/node/1342066
    $("#edit-anonymous-blazemeter-ahah-anon-page").val("Add Page");
    $("#edit-authenticated-blazemeter-ahah-auth-page").val("Add Page");

    var max_users = parseInt($("#edit-max-users").val());
    var median_users = max_users / 2;
    //anon slider
    $("#anon-slider").slider({
        min: 0,
        max: max_users,
        range: "min",
        value: $("#edit-anonymous-anon").val(),
        slide: function(event, ui) {
            $("#edit-anonymous-anon").val(ui.value);
            change_flag = true;
        }
    });
    $("#edit-anonymous-anon").change(function() {
        if (isNaN($("#edit-anonymous-anon").val())) {
            //User entered a string
            $("#edit-anonymous-anon").val(median_users);
        }
        if ($("#edit-anonymous-anon").val() > max_users) {
            $("#edit-anonymous-anon").val(max_users);
        }
        if ($("#edit-anonymous-anon").val() < 0) {
            $("#edit-anonymous-anon").val(0);
        }
        $("#anon-slider").slider("value", $("#edit-anonymous-anon").val());
    });

    //auth slider
    $("#auth-slider").slider({
        min: 0,
        max: max_users,
        range: "min",
        value: $("#edit-authenticated-auth").val(),
        slide: function(event, ui) {
            $("#edit-authenticated-auth").val(ui.value);
            change_flag = true;
        }
    });
    $("#edit-authenticated-auth").change(function() {
        if (isNaN($("#edit-authenticated-auth").val())) {
            //User entered a string
            $("#edit-authenticated-auth").val(0);
        }
        if ($("#edit-authenticated-auth").val() > max_users) {
            $("#edit-authenticated-auth").val(max_users);
        }
        if ($("#edit-authenticated-auth").val() < 0) {
            $("#edit-authenticated-auth").val(0);
        }
        $("#auth-slider").slider("value", $("#edit-authenticated-auth").val());
    });

    $("#blazemeter-signup").click(function() {
        $('#blazemeter-signup-modal').modal({
            closeHTML: "<div class='close-popup'><a href='#' title='Close'></a></div>",
            minHeight: 580,
            containerId: 'simplemodal-register-container'
        });
    });

    $("#blazemeter-login").click(function() {
        $('#blazemeter-login-modal').modal({
            closeHTML: "<div class='close-popup'><a href='#' title='Close'></a></div>",
            height: 370,
            minHeight: 370,
            containerId: 'simplemodal-login-container'
        });
    });

    $("#blazemeter-signup-modal #edit-submit-sign-up").click(function() {
	$('.reg-error-message').hide();
	    var password = $('#blazemeter-signup-modal #edit-reg-password').val();
        var email = $('#blazemeter-signup-modal #edit-reg-email').val();
        var first = $('#blazemeter-signup-modal #edit-reg-first-name').val();
        var last = $('#blazemeter-signup-modal #edit-reg-last-name').val();
		if(password.length >= 8 && validateEmail(email) && first.length > 0 && last.length > 0) {
		$('#blazemeter-signup-modal #edit-reg-password').attr('disabled', true);
		$('#blazemeter-signup-modal #edit-reg-email').attr('disabled', true);
		$('#blazemeter-signup-modal #edit-reg-first-name').attr('disabled', true);
		$('#blazemeter-signup-modal #edit-reg-last-name').attr('disabled', true);
		$('#blazemeter-signup-modal #edit-submit-sign-up').attr('disabled', true);
		$('.creating-account').show();
        
        $.ajax({
			  url: Drupal.settings.basePath + "?q=blazemeter_ajax/user-register",
			  type: "POST",
			  data: { "email": email,
			          "password": password,
			          "firstName": first,
			          "lastName": last
				   },
			  dataType: 'json',
			  success: function(response){
				  if(response.error == null) {
					  var apikey = response.result.user.apiKey;
	                    $.ajax({
	                        type: "GET",
	                        url: Drupal.settings.basePath + "?q=blazemeter/set_userkey/" + apikey,
	                        success: function() {
		                        location.reload();
	                        }
	                    });
				  } else {
					  $('.creating-account').hide();
					  $('.reg-error-message').show();
					  var res = response.error.message.split("Bad Request:");
					  if(!res[1]) {
						  res = response.error.message.split("Not Found:");
					  }
					  $('.reg-error-message').html(res[1]);
					  $('#blazemeter-signup-modal #edit-reg-password').attr('disabled', false);
					  $('#blazemeter-signup-modal #edit-reg-email').attr('disabled', false);
					  $('#blazemeter-signup-modal #edit-reg-first-name').attr('disabled', false);
					  $('#blazemeter-signup-modal #edit-reg-last-name').attr('disabled', false);
					  $('#blazemeter-signup-modal #edit-submit-sign-up').attr('disabled', false);
				  }
			  }
			});
		} else {
			if(password.length < 8) {
			$('#blazemeter-signup-modal #edit-reg-password').addClass('input-error-class');
			$('#blazemeter-signup-modal .form-item-reg-password').addClass('red');
			$("input[type='password']").val('');
			$('#blazemeter-signup-modal #edit-reg-password').attr('Placeholder', 'Please enter your password. Min 8 characters');
			}
			if(email.length < 1) {
			$('#blazemeter-signup-modal #edit-reg-email').addClass('input-error-class');
			$('#blazemeter-signup-modal .form-item-reg-email').addClass('red');
			$('#blazemeter-signup-modal #edit-reg-email').attr('Placeholder', 'Please enter your email');
			} else if(!validateEmail(email)) {
			$('#blazemeter-signup-modal #edit-reg-email').addClass('input-error-class');
			$('#blazemeter-signup-modal #edit-reg-email').val('');
			$('#blazemeter-signup-modal .form-item-reg-email').addClass('red');
			$('#blazemeter-signup-modal #edit-reg-email').attr('Placeholder', 'Please enter valid e-mail');
			}
			if(first.length < 1) {
			$('#blazemeter-signup-modal #edit-reg-first-name').addClass('input-error-class');
			$('#blazemeter-signup-modal .form-item-reg-first-name').addClass('red');
			$('#blazemeter-signup-modal #edit-reg-first-name').attr('Placeholder', 'Please enter your first name');
			}
			if(last.length < 1) {
			$('#blazemeter-signup-modal #edit-reg-last-name').addClass('input-error-class');
			$('#blazemeter-signup-modal .form-item-reg-last-name').addClass('red');
			$('#blazemeter-signup-modal #edit-reg-last-name').attr('Placeholder', 'Please enter your last name');
			}
		}
    });

    $("#blazemeter-login-modal #edit-submit-login").click(function() {
	    var password = $('#blazemeter-login-modal #edit-password').val();
        var email = $('#blazemeter-login-modal #edit-email').val();
        
		$('#blazemeter-login-modal #edit-password').attr('disabled', true);
		$('#blazemeter-login-modal #edit-email').attr('disabled', true);
		$('#blazemeter-login-modal #edit-submit-login').attr('disabled', true);
		$('.creating-account').show();
		$('.error-message').hide();
        var xmlhttp = new XMLHttpRequest();
		if(email.length > 0 && password.length >0 && validateEmail(email)) {
			$.ajax({
			  url: Drupal.settings.basePath + "?q=blazemeter_ajax/user-login",
			  type: "POST",
			  data: { "email": email,
			          "password": password
				   },
			  dataType: 'json',
			  success: function(response){
				  if(response.error == null) {
					  var apikey = response.result.apiKey;
	                    $.ajax({
	                        type: "GET",
	                        url: Drupal.settings.basePath + "?q=blazemeter/set_userkey/" + apikey,
	                        success: function() {
		                        location.reload();
	                        }
	                    });
				  } else {
					  $('.error-message').show();
					  var res = response.error.message.split("Bad Request:");
					  if(!res[1]) {
						  res = response.error.message.split("Not Found:");
					  }
					  $('.error-message').html(res[1]);
					  $('#blazemeter-login-modal #edit-password').attr('disabled', false);
					  $('#blazemeter-login-modal #edit-email').attr('disabled', false);
					  $('#blazemeter-login-modal #edit-submit-login').attr('disabled', false);
				  }
			  }
			});
		} else {
		$('.creating-account').hide();
		$('#blazemeter-login-modal #edit-password').attr('disabled', false);
		$('#blazemeter-login-modal #edit-email').attr('disabled', false);
		$('#blazemeter-login-modal #edit-submit-login').attr('disabled', false);
		if(password.length < 1){
		$('#blazemeter-login-modal #edit-password').addClass('input-error-class');
		$('#blazemeter-login-modal .form-item-password').addClass('red');
		$('#blazemeter-login-modal #edit-password').attr('Placeholder', 'Please enter your password');
		}
		if(email.length < 1){
		$('#blazemeter-login-modal #edit-email').addClass('input-error-class');
		$('#blazemeter-login-modal .form-item-email').addClass('red');
		$('#blazemeter-login-modal #edit-email').attr('Placeholder', 'Please enter your e-mail');
		}
		else if (!validateEmail(email)) {
		$('#blazemeter-login-modal #edit-email').addClass('input-error-class');
		$('#blazemeter-login-modal #edit-email').val('');
		$('#blazemeter-login-modal .form-item-email').addClass('red');
		$('#blazemeter-login-modal #edit-email').attr('Placeholder', 'Please enter valid e-mail');
		}
		}
    });
	
	function validateEmail(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
	};
	
	function check_userkey() {
		  var userkey = $("#edit-meta-userkey-holder").val();
		  if(userkey != 'user key is stored'){
			  $("#edit-buttons-goto").hide();
			  setTimeout(function() {
        	    check_userkey();
			  }, 1000);
		} else {
			$("#edit-buttons-goto").show();
		}
		}

    //Scenario
    $("#blazemeter-scenario .blazemeter-button").click(function() {
        var id = $(this).attr("id");
        if (id != $("#edit-meta-scenario").val()) {
            change_flag = true;
        }
        switch (id) {
            case "blazemeter-scenario-load":
                $("#edit-meta-scenario").val("load");
                break;
            case "blazemeter-scenario-stress":
                $("#edit-meta-scenario").val("stress");
                break;
            case "blazemeter-scenario-extreme":
                $("#edit-meta-scenario").val("extreme stress");
                break;
        }

        $("#blazemeter-scenario .blazemeter-button").removeClass("button-selected");
        $(this).addClass("button-selected");
    });

    if ($("#edit-meta-hasuserkey").val()) {
        $("#edit-meta-userkey-holder").val("user key is stored");
    }

    //Tooltips for scenario description
    $("#blazemeter-scenario-load").tooltip({
        position: "top right",
        relative: true,
        offset: [150, 250]
    });
    $("#blazemeter-scenario-stress").tooltip({
        position: "top right",
        relative: true,
        offset: [150, 192]
    });
    $("#blazemeter-scenario-extreme").tooltip({
        position: "top right",
        relative: true,
        offset: [150, 75]
    });

    $('#edit-meta-userkey-holder').keyup(function() {
        if ($('#password-password').val() != '') {
            $('#edit-meta-userkey').val($('#edit-meta-userkey-holder').val());
        }
    });

    $("#blazemeter-admin-settings-form #edit-buttons-goto").click(function() {
        $("#blazemeter-admin-settings-form .warning").remove();
        if (change_flag) {
            $("#blazemeter-admin-settings-form .submit-buttons").before("<div class='messages warning'>* The changes will not be saved until the Save button is clicked.</div>");
        }
    });

    $('#blazemeter-admin-settings-form').change(function() {
        change_flag = true;
    });
});
