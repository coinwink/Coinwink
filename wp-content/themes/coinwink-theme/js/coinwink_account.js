// USER FEEDBACK


jQuery("#feedback-link").click(function() {
	jQuery("#feedback-div").show();

	jQuery("#feedback-success").hide();
	jQuery("#feedback-error").hide();
	jQuery("#feedback-prep").show();
	jQuery(".cw-feedback-input").val("");

	jQuery("#btn-feedback-submit").show();
	jQuery("#loader-feedback-submit").hide();

	jQuery(".cw-feedback-input").focus();
});

jQuery("#np-modal-close").click(function(ev) {
	ev.preventDefault();
	closeFeedback();
});

function closeFeedback() {
	jQuery("#feedback-div").hide();
}

jQuery('#form-account-feedback').submit(function(e) {
	e.preventDefault();
	
	var formFeedback = jQuery(this).serialize();
	var formData = jQuery(this).serializeArray();

	jQuery.ajax({
		type:"POST",
		url: ajax_url,
		data: formFeedback+security_url,
		beforeSend: function() {
			jQuery("#btn-feedback-submit").hide();
			jQuery("#loader-feedback-submit").show();
		},
		success:function(data){
			if (data == 'success') {
				jQuery("#feedback-success").show();
				jQuery("#feedback-error").hide();
				jQuery("#feedback-prep").hide();

				userFeedbackFinish();
			}
			else {
				jQuery("#feedback-success").hide();
				jQuery("#feedback-error").show();
				jQuery("#feedback-prep").hide();

				userFeedbackFinish();
			}
		}
	});

	function userFeedbackFinish() {

		jQuery(document).one("click", function() {
			console.log('close');
			closeFeedback();
		});

		jQuery(document).one('keydown', function(event) {
			if (event.key == "Escape") {
				console.log('close');
				closeFeedback();
			}
		});

		
		jQuery("#np-modal-close").focus();
	}

});



// THEME

if (cw_theme == 'classic') {
	jQuery('#themeClassic').prop("checked", true);
}
else if (cw_theme == 'matrix') {
	jQuery('#themeMatrix').prop("checked", true);
}

if (isLoggedIn) {
	if (t_s) {
		jQuery('#static-check').prop("checked", true);
	}
}

var array = document.getElementsByClassName('black-stripe');
arrayReal = Array.from(array);

stopDemo = false;

function stopDemoNow() {
	stopDemo = true;
}

function runDemo() {
	if (stopDemo) { return; }
	setTimeout(function() {
		if (stopDemo) { return; }
		if(cw_theme == 'classic') {
			jQuery('.black-stripe').css('background-color', '#4F585B');
		}
		jQuery('.transition-matrix').css('z-index', 999);

		if(cw_theme == 'matrix') {
			setTimeout(function() {
				var styles = "<style type='text/css'>body::-webkit-scrollbar-track{background: black; } body::-webkit-scrollbar-thumb:hover { background-color: black; } body::-webkit-scrollbar-thumb{background: black}</style>";  
				jQuery(styles).appendTo('head');
			}, 500)
		}
		else if (cw_theme == 'classic') {
			setTimeout(function() {
				var styles = "<style type='text/css'>body::-webkit-scrollbar-track{background: #4F585B!important; box-shadow: none;} body::-webkit-scrollbar-thumb:hover { background-color: #4F585B; } body::-webkit-scrollbar-thumb{background: #4F585B}</style>";  
				jQuery(styles).appendTo('head');
			}, 500)
		}
	
		if(arrayReal.length != 0) {
			var item = arrayReal[Math.floor(Math.random() * arrayReal.length)];
			item.classList.add("full-height");
			arrayReal.splice(arrayReal.indexOf(item), 1);
			runDemo();
		}
		else {
			stopDemo = true;
			
			var string = 'Welcome to the Matrix';
			
			if(cw_theme == 'classic') {
				string = 'Coinwink Classic';
				jQuery('#t-centered').css('color', '#fff');
			}

			var i = 0;
			function typeWriter() {
				if (i < string.length) {
					document.getElementById("t-centered").innerHTML += string.charAt(i);
					i++;
					setTimeout(typeWriter, 25);
				}
			}

			setTimeout(function() {
				// jQuery('.t-centered').show();
				typeWriter();
			}, 1500);

			setTimeout(function() {
				window.location.href = homePath;
			}, 4500);
		}
	}, 50)
}
// runDemo();





jQuery('.themeMatrix').click(function() {
	if (jQuery('#themeMatrix').is(':checked')) {
		return;
	}
	jQuery('#themeMatrix').prop("checked", true);
	jQuery('#themeClassic').prop("checked", false);
	cw_theme = 'matrix';
	runDemo();
	var data = 'action=account_theme&theme=matrix';
	
	// Execute
	jQuery.ajax({
		type:"POST",
		url: ajax_url,
		data: data+security_url
	});
});

jQuery('.themeClassic').click(function() {
	if (jQuery('#themeClassic').is(':checked')) {
		return;
	}
	jQuery('#themeClassic').prop("checked", true);
	jQuery('#themeMatrix').prop("checked", false);
	cw_theme = 'classic';
	runDemo();

	var data = 'action=account_theme&theme=classic';
	jQuery.ajax({
		type:"POST",
		url: ajax_url,
		data: data+security_url
	});
});

jQuery('#static-check').click(function() {
	if (isAnim) {
		isAnim = false;
		t_s = 1;
		clearInterval(matrixAnim);
	}
	else {
		isAnim = true;
		t_s = false;
		bottomReached = false;
		clearInterval(matrixAnim);
		matrixAnim = setInterval(matrix, 50);
	}

	var data = 'action=theme_static&t_s='+t_s;
	jQuery.ajax({
		type:"POST",
		url: ajax_url,
		data: data+security_url
	});
})





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
	// ga('send', 'event', 'Account', 'Switch - SignupLogin', 'Login - switch');
});

jQuery("#signupSwitch").click(function() {
	jQuery(".acc-form").hide();
	jQuery(".switch-3-selection").css('border', '1px solid rgb(174, 174, 174)');
	window.location.hash = "#signup";
	jQuery("#signup").show();
	// ga('send', 'event', 'Account', 'Switch - SignupLogin', 'Signup - switch');
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
				// window.location.href = "http://localhost/coinwink/#subscription";
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


//
// Extra 100 credits button
//
jQuery('#buy_100_credits_button').click(function() {
	document.getElementById('credits-buy-button').innerHTML = 'Loading...'
	jQuery.ajax({
		type: "POST",
		url: ajax_url,
		data: 'action=stripe_order_100_credits' + security_url,
		complete: function(response) {
	
			if (response) {
		
				var data = JSON.parse(response.responseText);
				var stripe = Stripe('pk_live_6vLunpHAugckQPP96m7XYAot00eE2HMy01');
				// var stripe = Stripe('pk_test_OwUmU0ZHJyPSBxJ1K21Sn2CG0017xgE3Kq');
		
				stripe.redirectToCheckout({
					sessionId: data.id
				})
				.then(function (result) {
					console.error(result.error.message);
				});
	
			}
			
			else {
	
				console.error ('error');
	
			}
	
		}
	});
});