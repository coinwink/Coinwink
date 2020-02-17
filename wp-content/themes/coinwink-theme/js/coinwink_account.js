//
// jQuery dynamic link handler
//
function initialLoad() {
	var hash = window.location.hash;
	if (hash) {
		jQuery(".acc-form").hide();
		jQuery(hash).show();
		jQuery("html,body").animate({ scrollTop: 0 }, 100);
		if (hash == "#login") {
			jQuery(".switch-3-selection").css('border', '1px solid rgb(174, 174, 174)');
			jQuery("#loginSwitch").prop('checked', true);
		}
		else if (hash == "#signup") {
			jQuery(".switch-3-selection").css('border', '1px solid rgb(174, 174, 174)');
			jQuery("#signupSwitch").prop('checked', true);
    }
    else if (hash == "#signup_free") {
      jQuery('input:radio[name="plan"]').filter('[value="free"]').attr('checked', true);
      jQuery("#signup").show();
      jQuery("#signupSwitch").prop('checked', true);
      jQuery('#premium').hide();
      jQuery('#free').show();
    }
    else if (hash == "#cc_declined") {
      jQuery("#signup").show();
      jQuery("#signupSwitch").prop('checked', true);
      jQuery('#switch-event-free').removeClass( "active-toggle" );
      jQuery('#switch-event-premium').addClass( "active-toggle" );
      jQuery('#premium').show();
      jQuery('#cc_declined').show();
      jQuery('#free').hide();
    }
		else {
			jQuery("#signupSwitch").prop('checked', false);
			jQuery("#loginSwitch").prop('checked', false);
			jQuery(".switch-3-selection").css('border', '0px');
		}
	}
	else {
		jQuery("#signup").show();
		jQuery("#signupSwitch").prop('checked', true);
	}
}
window.onload = initialLoad();

window.onhashchange = function() {
	initialLoad();
}

jQuery(".hashlink").click(function() {
	initialLoad();
});


// SWITCH: Sign up <-> Log in
jQuery("#loginSwitch").click(function() {
	jQuery(".acc-form").hide();
	jQuery(".switch-3-selection").css('border', '1px solid rgb(174, 174, 174)');
	window.location.hash = "#login";
  jQuery("#login").show();
});

jQuery("#signupSwitch").click(function() {
	jQuery(".acc-form").hide();
	jQuery(".switch-3-selection").css('border', '1px solid rgb(174, 174, 174)');
	window.location.hash = "#signup";
  jQuery("#signup").show();
});


//
// Ajax to cancel subscription
//
jQuery('#cancel_subs_button').click(function() {
	if (confirm("Are you sure?") == true) {
		
		jQuery.ajax({
			type: "POST",
			url: ajax_url,
			data: 'action=cancel_subscription'+security_url,
			success: function(response){
				location.reload();
			}
		});

	}
});


//
// Ajax to delete acc
//
jQuery('#delete_my_acc_button').click(function() {
	if (confirm("Are you sure?") == true) {
		
		jQuery.ajax({
			type: "POST",
			url: ajax_url,
			data: 'action=delete_my_acc'+security_url,
			success: function(response){
				window.location.href = "https://coinwink.com";
			}
		});

	}
});
