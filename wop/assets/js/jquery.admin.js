
jQuery( document ).ready(function() {
    value = jQuery('#power_field').val();
    jQuery('#power_value').val(value)
    jQuery('#power_field').on('input',function() {

        value = jQuery(this).val();
        jQuery('#power_value').val(value)
    }) ;

});