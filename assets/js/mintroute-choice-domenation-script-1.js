let product_id = '';
let denomenation_id = '';
let account_id = '';
let zone_id    = '';
jQuery('#choice_domentation').change(function() {
    product_id = jQuery(this).attr('product-id');
    denomenation_id = jQuery(this).val();
    account_id = jQuery('.account_id').val() ? jQuery('.account_id').val() : '';
    zone_id = jQuery('.zone_id').val() ? jQuery('.zone_id').val() : '';
    call_ajax();
});

jQuery('.account_id').blur(function() {
    console.log(jQuery(this).val());
    account_id = jQuery(this).val();
    product_id = jQuery('#choice_domentation').attr('product-id');
    zone_id = jQuery('.zone_id').val() ? jQuery('.zone_id').val() : '';
    denomenation_id = jQuery('#choice_domentation').val();
    if(jQuery(this).attr('type') == 'email'){
        if( !validateEmail($account_id) ){
            jQuery('.single_add_to_cart_button').attr('disabled',true);
        } else {
            jQuery('.single_add_to_cart_button').attr('disabled',true);
        }
    }
    call_ajax();
});

jQuery('.zone_id').blur(function() {
    console.log(jQuery(this).val());
    zone_id = jQuery(this).val();
    account_id = jQuery('.account_id').val() ? jQuery('.account_id').val() : '';
    product_id = jQuery('#choice_domentation').attr('product-id');
    denomenation_id = jQuery('#choice_domentation').val();
    call_ajax();
});

function call_ajax() {
    jQuery.ajax({
        type: 'GET',
        url: mintroute_choice_domenation_price_cart_ajax.ajaxurl,
        data: {
            action: 'domenation_price_cart',
            denomenation_id: denomenation_id,
            product_id: product_id,
            account_id: account_id,
            zone_id   : zone_id
        },
        dataType: 'json',
        success: function(result) {
            if (typeof result._mintroute_denomination_ID !== 'undefined') {
                jQuery('.container-domenations .row-prices').show();
                jQuery('.container-domenations h2.show-price').html(result._mintroute_denomination_price);
                console.log(result);
            } else {
                jQuery('.container-domenations .row-prices').hide();
            }
            console.log(result);
        }
    });
}

function validateEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}