function stripeCheckout() {
  
  if (document.getElementById('agree').checked) {

    jQuery("#cc-pay-button").html('Please wait...');
    jQuery("#cc-pay-button").prop('disabled', true);
    jQuery("#cc-pay-button").addClass( "cc-pay-button-disabled" );

    
    jQuery.ajax({
      type: "POST",
      url:  '/api/stripe_order',
      complete: function(response) {

        if (response) {

          var data = JSON.parse(response.responseText);
          var stripe = Stripe(stripePubKey)

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
    
  }
  else {
    alert('Please read and agree to the Terms and Conditions'); return false;
  }
}

function stripeCheckoutStandard() {
  
  if (document.getElementById('agree').checked) {

    jQuery("#cc-pay-button").html('Please wait...');
    jQuery("#cc-pay-button").prop('disabled', true);
    jQuery("#cc-pay-button").addClass( "cc-pay-button-disabled" );

    
    jQuery.ajax({
      type: "POST",
      url:  '/api/stripe_order_standard',
      complete: function(response) {

        if (response) {

          var data = JSON.parse(response.responseText);
          var stripe = Stripe(stripePubKey)

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
    
  }
  else {
    alert('Please read and agree to the Terms and Conditions'); return false;
  }
}

function stripeBuy100Credits() {
	document.getElementById('credits-buy-button').innerHTML = 'Loading...'
	jQuery.ajax({
		type: "POST",
		url: "/api/stripe_order_100_credits",
		complete: function(response) {
	
			if (response) {
		
				var data = JSON.parse(response.responseText);
				var stripe = Stripe(stripePubKey);
		
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
}

//
// Ajax to cancel subscription
//
function stripeCancelSubs() {
	if (confirm("Are you sure?") == true) {
		
		jQuery.ajax({
			type: "POST",
			url: '/api/stripe_cancel_subscription',
			success: function(response){
        // console.log(response);
				location.reload();
			}
		});

	}
}


//
// Stripe checkout for suspended or cancelled subscription
//
function stripeCheckoutNew(type) {
  
  if (confirm("After the payment, your current subscription will be disabled permanently, and a new subscription will begin starting from the time your new payment is received.")) {
    
  jQuery(".new-subs-link").hide();
  jQuery("#new-subs-wait").show();

  var api_url = '';
  if (type == 'premium') {
    api_url = '/api/stripe_order';
  }
  else if (type == 'standard') {
    api_url = '/api/stripe_order_standard';
  }

  jQuery.ajax({
    type: "POST",
    url: api_url,
    complete: function(response) {

      if (response) {

        var data = JSON.parse(response.responseText);
        var stripe = Stripe(stripePubKey);

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
    
  }
  else {
    return;
  }
}
