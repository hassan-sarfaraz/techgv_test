@php
    $default_currency = DB::table('currencies')->where('is_default',1)->first();
    if($default_currency->id == Session::get('currency_id')){
        $currency_value = 1;
    }else{
        $session_currency = DB::table('currencies')->where('id',Session::get('currency_id'))->first();
        $currency_value = $session_currency->value;
    }
@endphp
<!--- one signal-->
@if(Request::path() == 'checkout')
<!------- //paypal -------->
<script type="text/javascript">
//notification
jQuery(document).on('click', '.shipping_data', function(e){
	getZonesBilling();
});

function getZonesBilling() {
	var field_name = jQuery('.shipping_data:checked');
	var mehtod_name = jQuery(field_name).attr('method_name');
	var shipping_price = jQuery(field_name).attr('shipping_price');
	jQuery("#mehtod_name").val(mehtod_name);
	jQuery("#shipping_price").val(shipping_price);
}

window.onload = function(e){

	var paypal_public_key = document.getElementById('paypal_public_key').value;
	var acount_type = document.getElementById('paypal_environment').value;

	if(acount_type=='Test'){
		var paypal_environment = 'sandbox'
	}else if(acount_type=='Live'){
		var paypal_environment = 'production'
	}

     paypal.Button.render({
		env: paypal_environment, // sandbox | production
		style: {
            label: 'checkout',
            size:  'small',    // small | medium | large | responsive
            shape: 'pill',     // pill | rect
            color: 'gold'      // gold | blue | silver | black
        },

		// PayPal Client IDs - replace with your own
		// Create a PayPal app: https://developer.paypal.com/developer/applications/create

		client: {
			sandbox:     paypal_public_key,
			production:  paypal_public_key
		},

		// Show the buyer a 'Pay Now' button in the checkout flow
		commit: true,

		// payment() is called when the button is clicked
		payment: function(data, actions) {
			var payment_currency = document.getElementById('payment_currency').value;
			var total_price = '<?php echo number_format((float)$total_price+0, 2, '.', '');?>';

			// Make a call to the REST api to create the payment
			return actions.payment.create({
				payment: {
					transactions: [
						{
							amount: { total: total_price, currency: payment_currency }
						}
					]
				}
			});
		},

		// onAuthorize() is called when the buyer approves the payment
		onAuthorize: function(data, actions) {

			// Make a call to the REST api to execute the payment
			return actions.payment.execute().then(function() {
			   	jQuery('#update_cart_form').prepend('<input type="hidden" name="nonce" value='+JSON.stringify(data)+'>');
				jQuery("#update_cart_form").submit();
			});
		}

	}, '#paypal_button');
};
</script>
<script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(e) {
	braintree.setup(
		// Replace this with a client token from your server
		" <?php print session('braintree_token')?>",
		"dropin", {
		container: "payment-form"
	});
});
</script>
<script src="{!! asset('web/js/stripe_card.js') !!}" data-rel-js></script>
<script type="application/javascript">
(function() {
  'use strict';
  var elements = stripe.elements({
    fonts: [{
        cssSrc: 'https://fonts.googleapis.com/css?family=Source+Code+Pro',
      }],
    // Stripe's examples are localized to specific languages, but if
    // you wish to have Elements automatically detect your user's locale,
    // use `locale: 'auto'` instead.
    locale: window.__exampleLocale
  });

  // Floating labels
  var inputs = document.querySelectorAll('.cell.example.example2 .input');
  Array.prototype.forEach.call(inputs, function(input) {
    input.addEventListener('focus', function() {
      input.classList.add('focused');
    });
    input.addEventListener('blur', function() {
      input.classList.remove('focused');
    });
    input.addEventListener('keyup', function() {
      if (input.value.length === 0) {
        input.classList.add('empty');
      } else {
        input.classList.remove('empty');
      }
    });
  });

  var elementStyles = {
    base: {
      color: '#32325D',
      fontWeight: 500,
      fontFamily: 'Source Code Pro, Consolas, Menlo, monospace',
      fontSize: '16px',
      fontSmoothing: 'antialiased',

      '::placeholder': {
        color: '#CFD7DF',
      },
      ':-webkit-autofill': {
        color: '#e39f48',
      },
    },
    invalid: {
      color: '#E25950',

      '::placeholder': {
        color: '#FFCCA5',
      },
    },
  };

  var elementClasses = {
    focus: 'focused',
    empty: 'empty',
    invalid: 'invalid',
  };

  var cardNumber = elements.create('cardNumber', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardNumber.mount('#example2-card-number');

  var cardExpiry = elements.create('cardExpiry', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardExpiry.mount('#example2-card-expiry');

  var cardCvc = elements.create('cardCvc', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardCvc.mount('#example2-card-cvc');

  registerElements([cardNumber, cardExpiry, cardCvc], 'example2');
})();
</script>
@endif

<script type="application/javascript">

@if(Request::path() != 'shop')
  jQuery(function() {
    jQuery( "#datepicker" ).datepicker({
      changeMonth: true,
      changeYear: true,
	  maxDate: '0',
    });
  });
@endif

/*const closeDialog = () => {
  const body = document.body;
  const scrollY = body.style.top;
  body.style.position = '';
  body.style.top = '';
  window.scrollTo(0, parseInt(scrollY || '0') * -1);
  document.getElementById('dialog').classList.remove('show');
}
window.addEventListener('scroll', () => {
  document.documentElement.style.setProperty('--scroll-y', `${window.scrollY}px`);
});*/

jQuery(document).on('click', '.alertTopclose', function(e){
		jQuery('#alertTop').hide();
});

jQuery( document ).ready( function () {
	jQuery('#promotionPop').modal({show:true});
	/*const body = document.body;
  body.style.position = 'fixed';*/
  /*$("#myModal").on("show.bs.modal", function () {
  var top = $("body").scrollTop(); $("body").css('position','fixed').css('overflow','hidden').css('top',-top).css('width','100%').css('height',top+5000);
}).on("hide.bs.modal", function () {
  var top = $("body").position().top; $("body").css('position','relative').css('overflow','auto').css('top',0).scrollTop(-top);
});*/
	jQuery('#loader').hide();

	@if($result['commonContent']['setting'][54]->value=='onesignal')
	 OneSignal.push(function () {
	  OneSignal.registerForPushNotifications();
	  OneSignal.on('subscriptionChange', function (isSubscribed) {
	   if (isSubscribed) {
		OneSignal.getUserId(function (userId) {
		 device_id = userId;
		 //ajax request
		 jQuery.ajax({
			 headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
			url: '{{ URL::to("/subscribeNotification")}}',
			type: "POST",
			data: '&device_id='+device_id,
			success: function (res) {},
		});
		 //$scope.oneSignalCookie();
		});
	   }
	  });

	 });
	@endif

	//load google map
    @if(Request::path() == 'contact-us')
        initialize();
    @endif

    @if(Request::path() == 'checkout')
        getZonesBilling();
        paymentMethods();
    @endif

$.noConflict();
	//stripe_ajax
jQuery(document).on('click', '#stripe_ajax', function(e){
	jQuery('#loader').css('display','flex');
	jQuery.ajax({
		headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
		url: '{{ URL::to("/stripeForm")}}',
		type: "POST",
		success: function (res) {
			if(res.trim() == "already added"){
			}else{
				jQuery('.head-cart-content').html(res);
				jQuery(parent).removeClass('cart');
				jQuery(parent).addClass('active');
			}
			message = "@lang('website.Product is added')";
			notification(message);
			jQuery('#loader').hide();
		},
	});
});
	//default product cart
jQuery(document).on('click', '.cart', function(e){
	var parent = jQuery(this);
	var products_id = jQuery(this).attr('products_id');
	var message ;
  jQuery(function ($) {
	jQuery.ajax({

		url: '{{ URL::to("/addToCart")}}',
    headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},

		type: "POST",
		data: '&products_id='+products_id,
		success: function (res) {
			console.log('gg');
			if(res.status == 'exceed'){
				swal("Something Happened To Stock", "@lang('website.Ops! Product is available in stock But Not Active For Sale. Please contact to the admin')", "error");
			}
			else{
				jQuery('.head-cart-content').html(res);
				jQuery(parent).removeClass('cart');
				jQuery(parent).addClass('active');
				jQuery(parent).html("@lang('website.Added')");
				@if(session()->get('locale') == 'en')
					swal("Congrates!", "Product Added Successfully Thanks.Continue Shopping", "success");
				@else
					swal("مبروك!", "تمت إضافة المنتج بنجاح ، شكرًا. استمر في التسوق", "success");
				@endif

			}

		},
	});
 });
});

jQuery(document).on('click', '.modal_show', function(e){
	var parent = jQuery(this);
	var products_id = jQuery(this).attr('products_id');
	var message ;
  jQuery(function ($) {
	jQuery.ajax({

		url: '{{ URL::to("/modal_show")}}',
    headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},

		type: "POST",
		data: '&products_id='+products_id,
		success: function (res) {
			jQuery("#products-detail").html(res);
			jQuery('#myModal').modal({show:true});
		},
	});
 });
});
	//commeents
jQuery(document).on('focusout','#order_comments', function(e){
	jQuery('#loader').css('display','flex');
	var comments = jQuery('#order_comments').val();
	jQuery.ajax({
		headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
		url: '{{ URL::to("/commentsOrder")}}',
		type: "POST",
		data: '&comments='+comments,
		async: false,
		success: function (res) {
			jQuery('#loader').hide();
		},
	});
});
		//hyperpayresponse
var resposne = jQuery('#hyperpayresponse').val();
if(typeof resposne  !== "undefined"){
	if(resposne.trim() =='success'){
		jQuery('#loader').css('display','flex');
		jQuery("#update_cart_form").submit();
	}else if(resposne.trim() =='error'){
		jQuery.ajax({
			headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
			url: '{{ URL::to("/checkout/payment/changeresponsestatus")}}',
			type: "POST",
			async: false,
			success: function (res) {
			},
		});
		jQuery('#paymentError').css('display','block');
	}
}
//cash_on_delivery_button

	//shipping_mehtods_form
jQuery(document).on('submit', '#shipping_mehtods_form', function(e){
	jQuery('.error_shipping').hide();
	var checked = jQuery(".shipping_data:checked").length > 0;
	if (!checked){
		jQuery('.error_shipping').show();
		return false;
	}
});
	//update_cart
jQuery(document).on('click', '#update_cart', function(e){
	jQuery('#loader').css('display','flex');
	jQuery("#update_cart_form").submit();
});
	//shipping_data

	//billling method
jQuery(document).on('click', '#same_billing_address', function(e){
		if(jQuery(this).prop('checked') == true){
			jQuery("#billing_firstname").val(jQuery("#firstname").val());
			jQuery("#billing_lastname").val(jQuery("#lastname").val());
			jQuery("#billing_company").val(jQuery("#company").val());
			jQuery("#billing_street").val(jQuery("#street").val());
			jQuery("#billing_city").val(jQuery("#city").val());
			jQuery("#billing_zip").val(jQuery("#postcode").val());
			jQuery("#billing_countries_id").val(jQuery("#entry_country_id").val());
			jQuery("#billing_zone_id").val(jQuery("#entry_zone_id").val());

			jQuery(".same_address").attr('readonly','readonly');
			jQuery(".same_address_select").attr('disabled','disabled');
		}else{
			jQuery(".same_address").removeAttr('readonly');
			jQuery(".same_address_select").removeAttr('disabled');
		}
});
	//apply_coupon_cart
jQuery(document).on('submit', '#apply_coupon', function(e){
		jQuery('#coupon_code').remove('error');
		jQuery('#coupon_require_error').hide();
		jQuery('#loader').css('display','flex');

		if(jQuery('#coupon_code').val().length > 0){
			var formData = jQuery(this).serialize();
			jQuery.ajax({
				headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
				url: '{{ URL::to("/apply_coupon")}}',
				type: "POST",
				data: formData,
				success: function (res) {
					console.log(res);
					var obj = JSON.parse(res);
					var message = obj.message;
					jQuery('#loader').hide();
					if(obj.success==0){
						jQuery("#coupon_error").html(message).show();
						return false;
					}else if(obj.success==2){
						jQuery("#coupon_error").html(message).show();
						return false;
					}else if(obj.success==1){
						window.location.reload(true);
					}
				},
			});
		}else{
			jQuery('#loader').css('display','none');
			jQuery('#coupon_code').addClass('error');
			jQuery('#coupon_require_error').show();
			return false;
		}
		jQuery('#loader').hide();
		return false;
});
	//coupon_code
jQuery(document).on('keyup', '#coupon_code', function(e){
		jQuery("#coupon_error").hide();
		if(jQuery(this).val().length >0){
			jQuery('#coupon_code').removeClass('error');
			jQuery('#coupon_require_error').hide();
		}else{
			jQuery('#coupon_code').addClass('error');
			jQuery('#coupon_require_error').show();
		}

});

jQuery(document).on('click', '.is_liked', function(e){

			var products_id = jQuery(this).attr('products_id');
			var selector = jQuery(this);
			jQuery('#loader').css('display','flex');
			var user_count = jQuery('#wishlist-count').html();
			jQuery.ajax({
	      beforeSend: function (xhr) { // Add this line
	              xhr.setRequestHeader('X-CSRF-Token', jQuery('[name="_csrfToken"]').val());
	      },
				url: '{{ URL::to("/likeMyProduct")}}',
				type: "POST",
				data: {"products_id":products_id,"_token": "{{ csrf_token() }}"},

				success: function (res) {
					var obj = JSON.parse(res);
					var message = obj.message;

					if(obj.success==0){
						jQuery('#alert-login-first').show();
						setTimeout(function() {
							jQuery('#alert-login-first').hide();
						}, 3000);

					}else if(obj.success==2){
						jQuery(selector).children('span').html(obj.total_likes);
						jQuery('#alert-liked').show();
						setTimeout(function() {
							jQuery('#alert-liked').hide();
						}, 3000);
					}else if(obj.success==1){
						jQuery('#alert-disliked').show();
						setTimeout(function() {
							jQuery('#alert-disliked').hide();
						}, 3000);
						jQuery(selector).children('span').html(obj.total_likes);
					}


				},
			});

		});
//sortby
jQuery(document).on('change', '.sortby', function(e){
	jQuery('#loader').css('display','flex');
	jQuery("#load_products_form").submit();
});

jQuery( function() {
		jQuery.widget( "custom.iconselectmenu", jQuery.ui.selectmenu, {
		  _renderItem: function( ul, item ) {
			var li = jQuery( "<li>" ),
			  wrapper = jQuery( "<div>", { text: item.label } );

			if ( item.disabled ) {
			  li.addClass( "ui-state-disabled" );
			}

			jQuery( "<span>", {
			  style: item.element.attr( "data-style" ),
			  "class": "ui-icon " + item.element.attr( "data-class" )
			})
			  .appendTo( wrapper );

			return li.append( wrapper ).appendTo( ul );
		  }
});

jQuery("#change_language")
		.iconselectmenu({
		  create: function (event, ui) {
			  var widget = jQuery(this).iconselectmenu("widget");
			  $span = jQuery('<span id="' + this.id + '_image" class="ui-selectmenu-image"> ').html("&nbsp;").appendTo(widget);
			  $span.attr("style", jQuery(this).children(":selected").data("style"));

		  },
		  change: function (event, ui) {
			  jQuery("#" + this.id + '_image').attr("style", ui.item.element.data("style"));
			  var locale = jQuery(this).val();
			  changeLanguage(locale);

		  }
		}).iconselectmenu("menuWidget").addClass("ui-menu-icons customicons");

  });

jQuery( function() {
    	jQuery( "#category_id" ).selectmenu();
		jQuery( ".attributes_data" ).selectmenu();
});



function validd() {
	var max = parseInt(document.detailform.quantity.max);
	var value = parseInt(document.detailform.quantity.value);

  if (value > max || value < 1) {

    jQuery('#alert-exceed').show();
     setTimeout(function() {
       jQuery('#alert-exceed').hide();
     }, 6000);
     document.detailform.quantity.focus();
     return false;
   }else{
		 var formData = jQuery("#add-Product-form").serialize();
	 	var url = jQuery('#checkout_url').val();
	 	var message;
	 	jQuery.ajax({
	 		url: '{{ URL::to("/addToCart")}}',
	 		headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},

	 		type: "POST",
	 		data: formData,

	 		success: function (res) {
				console.log('jj');
	 			if(res.trim() == "already added"){
	 				//notification
	 				message = 'Product is added!';
	 			}else{
	 				jQuery('.head-cart-content').html(res);
	 				message = 'Product is added!';
	 				jQuery(parent).addClass('active');
	 			}
	 				if(url.trim()=='true'){
	 					window.location.href = '{{ URL::to("/checkout")}}';
	 				}else{
	 					if(res == 'exceed'){
							swal("Something Happened To Stock", "@lang('website.Ops! Product is available in stock But Not Active For Sale. Please contact to the admin')", "error");
	 					}
	 					else {
							@if(session()->get('locale') == 'en')
								swal("Congrates!", "Product Added Successfully Thanks.Continue Shopping", "success");
							@else
								swal("مبروك!", "تمت إضافة المنتج بنجاح ، شكرًا. استمر في التسوق", "success");
							@endif

	 					}
	 				}
	 		},
	 	});
	 }
}

//add-to-Cart with custom options
jQuery(document).on('click', '.add-to-Cart', function(e){
	var formData = jQuery("#add-Product-form").serialize();
 var url = jQuery('#checkout_url').val();
 var message;
 jQuery.ajax({
	 url: '{{ URL::to("/addToCart")}}',
	 headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},

	 type: "POST",
	 data: formData,

	 success: function (res) {
		console.log('kk');
		 if(res['status'] == 'exceed'){
			 swal("Something Happened To Stock", "@lang('website.Ops! Product is available in stock But Not Active For Sale. Please contact to the admin')", "error");
		 }
		 else {
			 jQuery('.head-cart-content').html(res);
			 jQuery(parent).addClass('active');
			 swal("Congrates!", "Product Added Successfully Thanks.Continue Shopping", "success",{button: false});

		 }

		 }
 });
});

jQuery(document).on('click', '.add-to-Cart-from-detail', function(e){
	e.preventDefault();
			if(!validd()){ return false;}

});
//update-single-Cart with
jQuery(document).on('click', '.update-single-Cart', function(e){
	jQuery('#loader').css('display','flex');
	var formData = jQuery("#add-Product-form").serialize();
	var url = jQuery('#checkout_url').val();
	var message;
	jQuery.ajax({
		headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
		url: '{{ URL::to("/updatesinglecart")}}',
		type: "POST",
		data: formData,

		success: function (res) {
			if(res.trim() == "already added"){
				//notification
				message = 'Product is added!';
			}else{
				jQuery('.head-cart-content').html(res);
				message = 'Product is added!';
				jQuery(parent).addClass('active');
			}
				if(url.trim()=='true'){
					window.location.href = '{{ URL::to("/checkout")}}';
				}else{
					jQuery('#loader').css('display','none');
					//window.location.href = '{{ URL::to("/viewcart")}}';
					//message = "@lang('website.Product is added')";
					//notification(message);
				}
		},
	});
});
//validate form
jQuery(document).on('submit', '.form-validate', function(e){
var error = "";
jQuery(".field-validate").each(function() {
		if(this.value == '') {
			jQuery(this).closest(".form-group").addClass('has-error');
			jQuery(this).next(".error-content").removeAttr('hidden');
			error = "has error";
		}else{
			jQuery(this).closest(".form-group").removeClass('has-error');
			jQuery(this).next(".error-content").attr('hidden', true);
		}
});
var check = 0;
jQuery(".password").each(function() {
		var regex = "^\\s+$";
		if(this.value.match(regex)) {
			jQuery(this).closest(".form-group").addClass('has-error');
			jQuery(this).next(".error-content").removeAttr('hidden');
			error = "has error";
		}else{
			if(check == 1){
				 var res = passwordMatch();

					if(res=='matched'){
						jQuery('.password').closest(".form-group").removeClass('has-error');
						jQuery('#re_password').closest('.re-password-content').children('.error-content-password').add('hidden');
					}else if(res=='error'){
						jQuery('.password').closest(".form-group").addClass('has-error');
						jQuery('#re_password').closest('.re-password-content').children('.error-content-password').removeAttr('hidden');
						error = "has error";
					}
				}else{
					jQuery(this).closest(".form-group").removeClass('has-error');
					jQuery(this).next(".error-content").attr('hidden', true);
				}
				 check++;
			}

});

jQuery(".number-validate").each(function() {
		if(this.value == '' || isNaN(this.value)) {
			jQuery(this).closest(".form-group").addClass('has-error');
			jQuery(this).next(".error-content").removeAttr('hidden');
			error = "has error";
		}else{
			jQuery(this).closest(".form-group").removeClass('has-error');
			jQuery(this).next(".error-content").attr('hidden', true);
		}
});

jQuery(".email-validate").each(function() {
		var validEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
		if(this.value != '' && validEmail.test(this.value)) {
			jQuery(this).closest(".form-group").removeClass('has-error');
			jQuery(this).next(".error-content").attr('hidden', true);
		}else{
			jQuery(this).closest(".form-group").addClass('has-error');
			jQuery(this).next(".error-content").removeAttr('hidden');
			error = "has error";
		}
});

jQuery(".checkbox-validate").each(function() {

		if(jQuery(this).prop('checked') == true){
			jQuery(this).closest(".form-group").removeClass('has-error');
			jQuery(this).closest('.checkbox-parent').children('.error-content').attr('hidden', true);
		}else{
			jQuery(this).closest(".form-group").addClass('has-error');
			jQuery(this).closest('.checkbox-parent').children('.error-content').removeAttr('hidden');

			error = "has error";
		}

	});

	if(error=="has error"){
		return false;
	}
});
//focus form field
jQuery(document).on('keyup focusout change', '.field-validate', function(e){
	if(this.value == '') {
		jQuery(this).closest(".form-group").addClass('has-error');
		jQuery(this).next(".error-content").removeAttr('hidden');
	}else{
		jQuery(this).closest(".form-group").removeClass('has-error');
		jQuery(this).next(".error-content").attr('hidden', true);
	}
});
//focus form field
jQuery(document).on('keyup', '.number-validate', function(e){
	if(this.value == '' || isNaN(this.value)) {
		jQuery(this).closest(".form-group").addClass('has-error');
		jQuery(this).next(".error-content").removeAttr('hidden');
	}else{
		jQuery(this).closest(".form-group").removeClass('has-error');
		jQuery(this).next(".error-content").attr('hidden', true);
	}
});
//match password
jQuery(document).on('keyup focusout', '.password', function(e){
	var regex = "^\\s+$";
	if(this.value.match(regex)) {
		jQuery(this).closest(".form-group").addClass('has-error');
		jQuery(this).next(".error-content").removeAttr('hidden');
	}else{
		jQuery(this).closest(".form-group").removeClass('has-error');
		jQuery(this).next(".error-content").attr('hidden', true);
	}
});

jQuery(document).on('keyup focusout', '.email-validate', function(e){

	var validEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

	if(this.value != '' && validEmail.test(this.value)) {
		jQuery(this).closest(".form-group").removeClass('has-error');
		jQuery(this).next(".error-content").attr('hidden', true);
	}else{
		jQuery(this).closest(".form-group").addClass('has-error');
		jQuery(this).next(".error-content").removeAttr('hidden');
		error = "has error";
	}

});
	// This button will increment the value
	// jQuery('.qtyplus').click(function(e){
jQuery(document).on('click', '.qtyplus', function(e){
		console.log('fdddd');
		// Stop acting like a button
		e.preventDefault();
		// Get the field name
		fieldName = jQuery(this).attr('field');
		// Get its current value
		var currentVal = parseInt(jQuery(this).prev('.qty').val());
		var maximumVal =  jQuery('.qty').attr('max');
		//alert(maximum);
		// If is not undefined
		if (!isNaN(currentVal)) {
			if(maximumVal!=0){
				if(currentVal < maximumVal ){
					// Increment
					jQuery(this).prev('.qty').val(currentVal + 1);
				}
			}

		} else {
			// Otherwise put a 0 there
			jQuery(this).prev('.qty').val(0);
		}

		var qty = jQuery('.qty').val();




		var products_price = jQuery('#products_price').val();
		var total_price = parseFloat(qty) * parseFloat(products_price) * <?=$currency_value?>;
		jQuery('.total_price').html('<?=Session::get('symbol_left')?>'+' '+total_price.toFixed(2)+'<?=Session::get('symbol_right')?>');



		@if(isset($result['promotions'][0]) and !empty($result['promotions'][0]))

        let attributes_value = jQuery('option:selected', jQuery(".attributeid_0")).attr('attributes_value');

        if( attributes_value == {{ !empty($result['promotions'][0]) ? $result['promotions'][0]->promotion_attribute_id : 0 }}
            && parseFloat(qty) >= parseFloat({{$result['promotions'][0]->buy_frequency}}) ){
            console.log("qty", qty)
            jQuery(".promotion_product").show()
        }else{
            jQuery(".promotion_product").hide()
        }

		if(parseFloat({{$result['promotions'][0]->buy_frequency}}) > parseFloat({{$result['promotions'][0]->get_frequency}})){
            jQuery(".qty_promo").val(parseFloat(qty) / parseFloat({{$result['promotions'][0]->buy_frequency}}))
        }else{
            jQuery(".qty_promo").val(parseFloat(qty) * parseFloat({{$result['promotions'][0]->get_frequency}}))
        }
		@endif;

});
	// This button will decrement the value till 0
	//jQuery(".qtyminus").click(function(e) {
jQuery(document).on('click', '.qtyminus', function(e){

		// Stop acting like a button
		e.preventDefault();

		// Get the field name
		fieldName = jQuery(this).attr('field');
		var maximumVal =  jQuery('.qty').attr('max');
		var minimumVal =  jQuery('.qty').attr('min');

		// Get its current value
		var currentVal = parseInt(jQuery(this).next('.qty').val());
		// If it isn't undefined or its greater than 0
		if (!isNaN(currentVal) && currentVal > minimumVal) {
			// Decrement one
			jQuery(this).next('.qty').val(currentVal - 1);
		} else {
			// Otherwise put a 0 there
			jQuery(this).next('.qty').val(minimumVal);

		}

		var qty = jQuery('.qty').val();
		var products_price = jQuery('#products_price').val();
		var total_price = parseFloat(qty) * parseFloat(products_price) * <?=$currency_value?>;
		jQuery('.total_price').html('<?=Session::get('symbol_left')?>'+' '+total_price.toFixed(2)+'<?=Session::get('symbol_right')?>');



        @if(!empty($result['promotions'][0]))
        let attributes_value = jQuery('option:selected', jQuery(".attributeid_0")).attr('attributes_value');

        if( attributes_value == {{ !empty($result['promotions'][0]) ? $result['promotions'][0]->promotion_attribute_id : 0 }}
        && parseFloat(qty) >= parseFloat({{$result['promotions'][0]->buy_frequency}}) ){
            console.log("qty", qty)
            jQuery(".promotion_product").show()
        }else{
            jQuery(".promotion_product").hide()
        }

    if(parseFloat({{$result['promotions'][0]->buy_frequency}}) > parseFloat({{$result['promotions'][0]->get_frequency}})){
        jQuery(".qty_promo").val(parseFloat(qty) / parseFloat({{$result['promotions'][0]->buy_frequency}}))
    }else{
        jQuery(".qty_promo").val(parseFloat(qty) * parseFloat({{$result['promotions'][0]->get_frequency}}))
    }
    @endif;
});

    @if(!empty($result['promotions'][0]))
    var qty = jQuery('.qty').val();
    let attributes_value = jQuery('option:selected', jQuery(".attributeid_0")).attr('attributes_value');

    if( attributes_value == {{ !empty($result['promotions'][0]) ? $result['promotions'][0]->promotion_attribute_id : 0 }}
        && parseFloat(qty) >= parseFloat({{$result['promotions'][0]->buy_frequency}}) ){
        console.log("qty", qty)
        jQuery(".promotion_product").show()
    }else{
        jQuery(".promotion_product").hide()
    }

    if(parseFloat({{$result['promotions'][0]->buy_frequency}}) > parseFloat({{$result['promotions'][0]->get_frequency}})){
        jQuery(".qty_promo").val(parseFloat(qty) / parseFloat({{$result['promotions'][0]->buy_frequency}}))
    }else{
        jQuery(".qty_promo").val(parseFloat(qty) * parseFloat({{$result['promotions'][0]->get_frequency}}))
    }
    @endif;
	// This button will increment the value
jQuery('.qtypluscart').click(function(e){
		e.preventDefault();
		// Get the field name
		fieldName = jQuery(this).attr('field');
		// Get its current value
		var currentVal = parseInt(jQuery(this).prev('.qty').val());
		var maximumVal =  jQuery(this).prev('.qty').attr('max');


		//alert(maximum);
		// If is not undefined
		if (!isNaN(currentVal)) {
			if(maximumVal!=0){
				if(currentVal < maximumVal ){
					// Increment
					jQuery(this).prev('.qty').val(currentVal + 1);
				}
			}

		} else {
			// Otherwise put a 0 there
			jQuery(this).prev('.qty').val(0);
		}
});

jQuery(".qtyminuscart").click(function(e) {

		// Stop acting like a button
		e.preventDefault();

		// Get the field name
		fieldName = jQuery(this).attr('field');

		// Get its current value
		var currentVal = parseInt(jQuery(this).next('.qty').val());
		var minimumVal =  jQuery(this).next('.qty').attr('min');
		// If it isn't undefined or its greater than 0
		if (!isNaN(currentVal) && currentVal > minimumVal) {
			// Decrement one
			jQuery(this).next('.qty').val(currentVal - 1);
		} else {
			// Otherwise put a 0 there
			jQuery(this).next('.qty').val(minimumVal);

		}


});

function cart_item_price(){

		var subtotal = 0;
		jQuery(".cart_item_price").each(function() {
			subtotal= parseFloat(subtotal) + parseFloat(jQuery(this).val()) * <?=$currency_value?>;
		});
		jQuery('#subtotal').html('<?=Session::get('symbol_left')?>'+subtotal+'<?=Session::get('symbol_right')?>');

		var discount = 0;
		jQuery(".discount_price_hidden").each(function() {
			discount =  parseFloat(discount) - parseFloat(jQuery(this).val());
		});

		jQuery('.discount_price').val(Math.abs(discount));

		jQuery('#discount').html('<?=Session::get('symbol_left')?>'+Math.abs(discount) * <?=$currency_value?>+'<?=Session::get('symbol_right')?>');

		//total value
		var total_price = parseFloat(subtotal) - parseFloat(discount) * <?=$currency_value?>;
		jQuery('#total_price').html('<?=Session::get('symbol_left')?>'+total_price+'<?=Session::get('symbol_right')?>');
};
	//default_address
jQuery(document).on('click', '.default_address', function(e){
		jQuery('#loader').css('display','flex');
		var address_id = jQuery(this).attr('address_id');
		jQuery.ajax({
			headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
			url: '{{ URL::to("/myDefaultAddress")}}',
			type: "POST",
			data: '&address_id='+address_id,

			success: function (res) {
				 window.location = 'shipping-address?action=default';
			},

		});

});
	//deleteMyAddress
jQuery('.slide-toggle').on('click', function(event){
 jQuery('.color-panel').toggleClass('active');
});

jQuery( function() {
	  var maximum_price = jQuery( ".maximum_price" ).val();
	  jQuery( "#slider-range" ).slider({
		range: true,
		min: 0,
		max: maximum_price,
		values: [ 0, maximum_price ],
		slide: function( event, ui ) {
			jQuery('#min_price').val(ui.values[ 0 ] );
			jQuery('#max_price').val(ui.values[ 1 ] );

			jQuery('#min_price_show').val( ui.values[ 0 ] );
			jQuery('#max_price_show').val( ui.values[ 1 ] );
		},
		create: function(event, ui){
			jQuery(this).slider('value',20);
		}
	   });
	   jQuery( "#min_price_show" ).val( jQuery( "#slider-range" ).slider( "values", 0 ) );
	   jQuery( "#max_price_show" ).val(jQuery( "#slider-range" ).slider( "values", 1 ) );
	   //jQuery( "#slider-range" ).slider( "option", "max", 50 );
});
//tooltip enable
jQuery(function () {
  jQuery('[data-toggle="tooltip"]').tooltip()
});

function initialize(location){

		@if(!empty($result['commonContent']['setting'][9]->value) or $result['commonContent']['setting'][10]->value)
			var address = '{{$result['commonContent']['setting'][9]->value}}, {{$result['commonContent']['setting'][10]->value}}';
		@else
			var address = '';
		@endif

		var map = new google.maps.Map(document.getElementById('googleMap'), {
			mapTypeId: google.maps.MapTypeId.TERRAIN,
			zoom: 13
		});
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({
			'address': address
		},
		function(results, status) {
			if(status == google.maps.GeocoderStatus.OK) {
			 new google.maps.Marker({
				position: results[0].geometry.location,
				map: map
			 });
			 map.setCenter(results[0].geometry.location);
			}
		});
	   }
	//default product cart

});
//ready doument end
jQuery('.dropdown-menu').on('click', function(event){
	// The event won't be propagated up to the document NODE and
	// therefore delegated events won't be fired
			event.stopPropagation();
		});
		jQuery(".alert.fade").fadeTo(2000, 500).slideUp(500, function(){
		    jQuery(".alert.fade").slideUp(500);
		});

		function delete_cart_product(cart_id){
			jQuery('#loader').css('display','flex');
			var id = cart_id;
			jQuery.ajax({
				headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
				url: '{{ URL::to("/deleteCart")}}',
				type: "GET",
				data: '&id='+id+'&type=header cart',
				success: function (res) {
					// window.location.reload(true);
				},
			});
};
//paymentMethods
function paymentMethods(){
	//jQuery('#loader').css('display','flex');
	var payment_method = jQuery(".payment_method:checked").val();
	jQuery(".payment_btns").hide();

	jQuery("#"+payment_method+'_button').show();
	jQuery.ajax({
		headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
		url: '{{ URL::to("/paymentComponent")}}',
		type: "POST",
		data: '&payment_method='+payment_method,
		success: function (res) {
			//jQuery('#loader').hide();
		},
	});
}
//pay_instamojo
jQuery(document).on('click', '#pay_instamojo', function(e){
	var formData = jQuery("#instamojo_form").serialize();
	jQuery.ajax({
		headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
		url: '{{ URL::to("/pay-instamojo")}}',
		type: "POST",
		data: formData,
		success: function (res) {
			var data = JSON.parse(res);

			var success = data.success;
			if(success==false){
				var phone = data.message.phone;
				var email = data.message.email;

				if(phone != null){
					var message = phone;
				}else if(email != null){
					var message = email;
				}else{
					var message = 'Something went wrong!';
				}

				jQuery('#insta_mojo_error').show();
				jQuery('#instamojo-error-text').html(message);

			}else{
				jQuery('#insta_mojo_error').hide();
				jQuery('#instamojoModel').modal('hide');
				jQuery('#update_cart_form').prepend('<input type="hidden" name="nonce" value='+JSON.stringify(data)+'>');
				jQuery("#update_cart_form").submit();
			}

		},
	});

});

//pay_payd
jQuery(document).on('click', '#pay_payd', function(e){
    var formData = jQuery("#payd_form").serialize();
    jQuery.ajax({
        headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
        url: '{{ URL::to("/pay-payd")}}',
        type: "POST",
        data: formData,
        success: function (res) {
            // var data = JSON.parse(res);
            if(res)
            {
                window.location.href = res;
            }
        },
    });

});

function passwordMatch(){

	var password = jQuery('#password').val();
	var re_password = jQuery('#re_password').val();

	if(password == re_password){
		return 'matched';
	}else{
		return 'error';
	}
}

function getZones() {
		jQuery(function ($) {
			jQuery('#loader').css('display','flex');
			var country_id = jQuery('#entry_country_id').val();
			jQuery.ajax({
				beforeSend: function (xhr) { // Add this line
								xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				 },
				url: '{{ URL::to("/ajaxZones")}}',
				type: "POST",
				//data: '&country_id='+country_id,
				 data: {'country_id': country_id,"_token": "{{ csrf_token() }}"},

				success: function (res) {
					var i;
					var showData = [];
					for (i = 0; i < res.length; ++i) {
						var j = i + 1;
						showData[i] = "<option value='"+res[i].zone_id+"'>"+res[i].zone_name+"</option>";
					}
					showData.push("<option value='-1'>@lang('website.Other')</option>");
					jQuery("#entry_zone_id").html(showData);
					jQuery('#loader').hide();
				},
			});
		});
};

function getBillingZones() {
	console.log('here');
	jQuery('#loader').css('display','flex');
	var country_id = jQuery('#billing_countries_id').val();
	jQuery.ajax({
		headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
		url: '{{ URL::to("/ajaxZones")}}',
		type: "POST",
		 data: {'country_id': country_id},

		success: function (res) {
			var i;
			var showData = [];
			for (i = 0; i < res.length; ++i) {
				var j = i + 1;
				showData[i] = "<option value='"+res[i].zone_id+"'>"+res[i].zone_name+"</option>";
			}
			showData.push("<option value='Other'>@lang('website.Other')</option>");
			jQuery("#billing_zone_id").html(showData);
			jQuery('#loader').hide();
		},
	});

};


'use strict';
function showPreview(objFileInput) {
	if (objFileInput.files[0]) {
		var fileReader = new FileReader();
		fileReader.onload = function (e) {
			jQuery("#uploaded_image").html('<img src="'+e.target.result+'" width="150px" height="150px" class="upload-preview" />');
			jQuery("#uploaded_image").css('opacity','1.0');
			jQuery(".upload-choose-icon").css('opacity','0.8');
		}
		fileReader.readAsDataURL(objFileInput.files[0]);
	}
}

jQuery(document).ready(function() {
  /******************************
      BOTTOM SCROLL TOP BUTTON
   ******************************/

  // declare variable
  var scrollTop = jQuery(".floating-top");

  jQuery(window).scroll(function() {
    // declare variable
    var topPos = jQuery(this).scrollTop();

    // if user scrolls down - show scroll to top button
    if (topPos > 150) {
      jQuery(scrollTop).css("opacity", "1");

    } else {
      jQuery(scrollTop).css("opacity", "0");
    }
});
  //Click event to scroll to top
jQuery(scrollTop).click(function() {
    jQuery('html, body').animate({
      scrollTop: 0
    }, 800);
    return false;

  });
});

jQuery('body').on('mouseenter mouseleave','.dropdown.open',function(e){
  var _d=jQuery(e.target).closest('.dropdown');
  _d.addClass('show');
  setTimeout(function(){
    _d[_d.is(':hover')?'addClass':'removeClass']('show');

  },300);
  jQuery('.dropdown-menu', _d).attr('aria-expanded',_d.is(':hover'));
});

jQuery('.nav-index').on('show.bs.tab', function (e) {
	  console.log('fire');
	  e.target // newly activated tab
	  e.relatedTarget // previous active tab
	  jQuery('.overlay').show();
})

jQuery('.nav-index').on('hidden.bs.tab', function (e) {
	  console.log('expire');
	  e.target // newly activated tab
	  e.relatedTarget // previous active tab
	  jQuery('.overlay').hide();
})

function cancelOrder() {
	if (confirm("@lang('website.Are you sure you want to cancel this order?')")) {
		return true;
	} else {
		return false;
	}
}

function returnOrder() {
	if (confirm("@lang('website.Are you sure you want to return this order?')")) {
		return true;
	} else {
		return false;
	}
}

jQuery(document).ready(function() {

  @if(!empty($result['detail']['product_data'][0]->attributes))
    @foreach( $result['detail']['product_data'][0]->attributes as $key=>$attributes_data )
        @php
            $functionValue = 'attributeid_'.$key;
            $attribute_sign = 'attribute_sign_'.$key++;
        @endphp

  //{{ $functionValue }}();
	function {{ $functionValue }}(){
	    var value_price = jQuery('option:selected', ".{{$functionValue}}").attr('value_price');
	    jQuery("#{{ $functionValue }}").val(value_price);
	  }
	  //change_options
	jQuery(document).on('change', '.{{ $functionValue }}', function(e){
		    var {{ $functionValue }} = jQuery("#{{ $functionValue }}").val();
		    var old_sign = jQuery("#{{ $attribute_sign }}").val();

		    let attributes_value = jQuery('option:selected', jQuery(".attributeid_0")).attr('attributes_value');
            var qty = jQuery('.qty').val();

            if( attributes_value == {{ !empty($result['promotions'][0]) ? $result['promotions'][0]->promotion_attribute_id : 0 }}
                && parseFloat(qty) >= parseFloat({{$result['promotions'][0]->buy_frequency ?? 0}}) ){
                jQuery(".promotion_product").show()
            }else{
                jQuery(".promotion_product").hide()
            }

		    var value_price = jQuery('option:selected', this).attr('value_price');
		    var prefix = jQuery('option:selected', this).attr('prefix');
		    var current_price = jQuery('#products_price').val();
		    var {{ $attribute_sign }} = jQuery("#{{ $attribute_sign }}").val(prefix);

		    if(old_sign.trim()=='+'){
		      var current_price = current_price - {{ $functionValue }};
		    }

		    if(old_sign.trim()=='-'){
		      var current_price = parseFloat(current_price) + parseFloat({{ $functionValue }});
		    }

		    if(prefix.trim() == '+' ){
		      var total_price = parseFloat(current_price) + parseFloat(value_price);
		    }
		    if(prefix.trim() == '-' ){
		      total_price = current_price - value_price;
		    }

		    jQuery("#{{ $functionValue }}").val(value_price);
		    jQuery('#products_price').val(total_price);
		    var qty = jQuery('.qty').val();
		    var products_price = jQuery('#products_price').val();
		    var total_price = qty * products_price * <?=$currency_value?>;
		    jQuery('.total_price').html('<?=Session::get('symbol_left')?>'+' '+total_price.toFixed(2)+'<?=Session::get('symbol_right')?>');

	});
	@endforeach

	calculateAttributePrice();
	function calculateAttributePrice(){
	  var products_price = jQuery('#products_price').val();
	  var p_constant = '<?= Config::get('global.p_constant') ?>';
	    var tax = (parseFloat(products_price) * parseFloat(p_constant));
	    products_price = parseFloat(tax) + parseFloat(products_price);
		products_price = Math.round(products_price, 2);

	  jQuery(".currentstock").each(function() {
	    var value_price  = jQuery('option:selected', this).attr('value_price');
	    var prefix = jQuery('option:selected', this).attr('prefix');

	    if(prefix.trim()=='+'){
        	products_price = parseFloat(products_price) + parseFloat(value_price);
	    }

	    if(prefix.trim()=='-'){
        	products_price = parseFloat(products_price) - parseFloat(value_price);
	    }

	  });
	  jQuery('#products_price').val(products_price);
	  jQuery('.total_price').html('<?=Session::get('symbol_left')?>'+' '+products_price.toFixed(2)+'<?=Session::get('symbol_right')?>');
	}

	@endif

});


	@if(!empty($result['detail']['product_data'][0]->products_type) and $result['detail']['product_data'][0]->products_type==1)
		getQuantity();
		cartPrice();
	@endif

function cartPrice(){
	var i = 0;
	jQuery(".currentstock").each(function() {
		var value_price = jQuery('option:selected', this).attr('value_price');
		var attributes_value = jQuery('option:selected', this).attr('attributes_value');
		var prefix = jQuery('option:selected', this).attr('prefix');
		jQuery('#attributeid_' + i).val(value_price);
		jQuery('#attribute_sign_' + i++).val(prefix);

	});
}

//ajax call for add option value
function getQuantity(){
	var attributeid = [];
	var i = 0;

	jQuery(".currentstock").each(function() {
		var value_price = jQuery('option:selected', this).attr('value_price');
		var attributes_value = jQuery('option:selected', this).attr('attributes_value');
		jQuery('#function_' + i).val(value_price);
		jQuery('#attributeids_' + i++).val(attributes_value);
	});

	var formData = jQuery('#add-Product-form').serialize();
	jQuery.ajax({
		headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
		url: '{{ URL::to("getquantity")}}',
		type: "POST",
		data: formData,
		dataType: "json",
		success: function (res) {

			jQuery('#current_stocks').html(res.remainingStock);
			var min_level = 0;
			var max_level = 0;
			var inventory_ref_id = res.inventory_ref_id;

			if(res.minMax != ''){
				min_level = res.minMax[0].min_level;
				max_level = res.minMax[0].max_level;
			}

			if(res.remainingStock>0){
				jQuery('.stock-cart').removeAttr('hidden');
				jQuery('.stock-out-cart').attr('hidden',true);
				var max_order = jQuery('#max_order').val();

				if(max_order.trim()!=0){
					if(max_order.trim()>=res.remainingStock){
						jQuery('.qty').attr('max',res.remainingStock);
					}else{
						jQuery('.qty').attr('max',max_order);
					}
				}else{
					jQuery('.qty').attr('max',res.remainingStock);
				}
			}else{
				jQuery('.stock-out-cart').removeAttr('hidden');
				jQuery('.stock-cart').attr('hidden',true);
				jQuery('.qty').attr('max',0);
			}

		},
	});
}
</script>
<!--- google map-->
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&libraries=geometry&key=AIzaSyCQq_d3bPGfsIAlenXUG5RtZsKZKzOmrMw"></script>
<!-- Include js plugin -->
<!-- Include js plugin -->

<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script src="https://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script src="{{asset('web/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('web/js/owl.carousel.min.js')}}"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script src="{{asset('web/js/slick.min.js')}}"></script>
<script type="text/javascript" src="{{asset('web/api/fancybox/source/jquery.fancybox.js')}}"></script>

@if(session('direction') == 'rtl')
<script src="{{asset('web/js/rtl_scripts.js')}}"></script>
@else
<script src="{{asset('web/js/scripts.js')}}"></script>
@endif
<!-- <script src="{{asset('web/js/setup.js')}}"></script> -->

<script>

// Fancy Box For Product Detail Page
jQuery(document).ready(function() {
    jQuery(".fancybox-button").fancybox({
        openEffect  : 'none',
        closeEffect : 'none',
        prevEffect		: 'none',
        nextEffect		: 'none',
        closeBtn		: true,
        margin      : [20, 60, 20, 60],
        helpers		: {
            title	: { type : 'inside' },
            buttons	: {}
        }
    });
});

// Product SLICK
jQuery('.slider-for').slick({
    slidesToShow: 1,
    slidesToScroll:1,
    arrows: false,
    infinite: false,
    draggable: false,
    fade: true,
    asNavFor: '.slider-nav'
});
jQuery('.slider-nav').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: '.slider-for',
    centerMode: true,
    centerPadding: '60px',
    dots: false,
    arrows: true,
    focusOnSelect: true
});

// Product vertical SLICK
jQuery('.slider-for-vertical').slick({
    slidesToShow: 1,
    slidesToScroll:1,
    arrows: false,
    infinite: false,
    draggable: false,
    fade: true,
    asNavFor: '.slider-nav-vertical'
});
jQuery('.slider-nav-vertical').slick({
    dots: false,
    arrows: true,
    vertical: true,
    asNavFor: '.slider-for-vertical',
    slidesToShow: 3,
    // centerMode: true,
    slidesToScroll: 1,
    verticalSwiping: true,
    focusOnSelect: true
});

// Zoom Core Scripts

(function(o){var t={url:!1,callback:!1,target:!1,duration:120,on:"mouseover",touch:!0,onZoomIn:!1,onZoomOut:!1,magnify:1};o.zoom=function(t,n,e,i){var u,c,a,r,m,l,s,f=o(t),h=f.css("position"),d=o(n);return t.style.position=/(absolute|fixed)/.test(h)?h:"relative",t.style.overflow="hidden",e.style.width=e.style.height="",o(e).addClass("zoomImg").css({position:"absolute",top:0,left:0,opacity:0,width:e.width*i,height:e.height*i,border:"none",maxWidth:"none",maxHeight:"none"}).appendTo(t),{init:function(){c=f.outerWidth(),u=f.outerHeight(),n===t?(r=c,a=u):(r=d.outerWidth(),a=d.outerHeight()),m=(e.width-c)/r,l=(e.height-u)/a,s=d.offset()},move:function(o){var t=o.pageX-s.left,n=o.pageY-s.top;n=Math.max(Math.min(n,a),0),t=Math.max(Math.min(t,r),0),e.style.left=t*-m+"px",e.style.top=n*-l+"px"}}},o.fn.zoom=function(n){return this.each(function(){var e=o.extend({},t,n||{}),i=e.target&&o(e.target)[0]||this,u=this,c=o(u),a=document.createElement("img"),r=o(a),m="mousemove.zoom",l=!1,s=!1;if(!e.url){var f=u.querySelector("img");if(f&&(e.url=f.getAttribute("data-src")||f.currentSrc||f.src),!e.url)return}c.one("zoom.destroy",function(o,t){c.off(".zoom"),i.style.position=o,i.style.overflow=t,a.onload=null,r.remove()}.bind(this,i.style.position,i.style.overflow)),a.onload=function(){function t(t){f.init(),f.move(t),r.stop().fadeTo(o.support.opacity?e.duration:0,1,o.isFunction(e.onZoomIn)?e.onZoomIn.call(a):!1)}function n(){r.stop().fadeTo(e.duration,0,o.isFunction(e.onZoomOut)?e.onZoomOut.call(a):!1)}var f=o.zoom(i,u,a,e.magnify);"grab"===e.on?c.on("mousedown.zoom",function(e){1===e.which&&(o(document).one("mouseup.zoom",function(){n(),o(document).off(m,f.move)}),t(e),o(document).on(m,f.move),e.preventDefault())}):"click"===e.on?c.on("click.zoom",function(e){return l?void 0:(l=!0,t(e),o(document).on(m,f.move),o(document).one("click.zoom",function(){n(),l=!1,o(document).off(m,f.move)}),!1)}):"toggle"===e.on?c.on("click.zoom",function(o){l?n():t(o),l=!l}):"mouseover"===e.on&&(f.init(),c.on("mouseenter.zoom",t).on("mouseleave.zoom",n).on(m,f.move)),e.touch&&c.on("touchstart.zoom",function(o){o.preventDefault(),s?(s=!1,n()):(s=!0,t(o.originalEvent.touches[0]||o.originalEvent.changedTouches[0]))}).on("touchmove.zoom",function(o){o.preventDefault(),f.move(o.originalEvent.touches[0]||o.originalEvent.changedTouches[0])}).on("touchend.zoom",function(o){o.preventDefault(),s&&(s=!1,n())}),o.isFunction(e.callback)&&e.callback.call(a)},a.src=e.url})},o.fn.zoom.defaults=t})(window.jQuery);

jQuery(function(){
    // ZOOM
    jQuery('.ex1').zoom();

});
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>