/**
* Admin Javascript Functionality
* 
* @package js
* @author Tim Carr
*/
jQuery.noConflict();
jQuery(document).ready(function() {    
    // Enctype
    jQuery('form[name=post]').attr('enctype', 'multipart/form-data');
    jQuery('form[name=post]').attr('encoding', 'multipart/form-data');
    
    // jQuery Colorpicker
    jQuery('.wpsp-color-holder').each(function(i) {
        var defaultColor = jQuery('input[class=wpsp-hex]', jQuery(this)).val();
        jQuery(this).ColorPicker({
            flat: true,
            color: defaultColor,
            onChange: function(hsb, hex, rgb) {
                jQuery('input[class=wpsp-hex]', jQuery(this).parent()).val(hex); // Stores hex value in hidden field when changed in colorpicker
            },
            onSubmit: function(hsb, hex, rgb) {
                jQuery('input[class=wpsp-hex]', jQuery(this).parent()).val(hex); // Stores hex value in hidden field when submitted in colorpicker    
            }
        });
    });
    
    // Fade Messages
    setTimeout(function() { jQuery('div.wpsp-fade').fadeOut('slow', function() { jQuery(this).remove(); }); }, 3000);
    
    // Opt In Form on load
    switch (jQuery("select[name='wpsp[template][optInProvider]']").val()) {
        case 'aWeber':
            jQuery("label[for='wpsp[template][optInUsername]']").html('Opt In List Username');
            jQuery("label[for='wpsp[template][optInListID]']").html('Opt In List Name or ID');
            break;
        case 'MailChimp':
            jQuery("label[for='wpsp[template][optInUsername]']").html('Opt In List API Key');
            jQuery("label[for='wpsp[template][optInListID]']").html('Opt In List ID Number');
            break;
        case 'GetResponse':
            jQuery("label[for='wpsp[template][optInUsername]']").html('Opt In List API Key');
            jQuery("label[for='wpsp[template][optInListID]']").html('Opt In List Campaign Name');
            break;
        case '(none)':
        default:
            jQuery("input[name='wpsp[template][optInUsername]']").val('');
            jQuery("input[name='wpsp[template][optInUsername]']").attr('disabled',true);
            jQuery("input[name='wpsp[template][optInListID]']").val('');
            jQuery("input[name='wpsp[template][optInListID]']").attr('disabled',true);
            break;     
    }

    // Opt In Form on value change
    jQuery("select[name='wpsp[template][optInProvider]']").bind('change', function() {
        if (jQuery(this).val() == '(none)') {
            // Disable opt in username and list ID fields
            jQuery("input[name='wpsp[template][optInUsername]']").val('');
            jQuery("input[name='wpsp[template][optInUsername]']").attr('disabled',true);
            jQuery("input[name='wpsp[template][optInListID]']").val('');
            jQuery("input[name='wpsp[template][optInListID]']").attr('disabled',true);                
        } else {
            // Enable opt in username and list ID fields
            jQuery("input[name='wpsp[template][optInUsername]']").removeAttr('disabled');
            jQuery("input[name='wpsp[template][optInListID]']").removeAttr('disabled');
        }
        
        switch (jQuery(this).val()) {
            case 'MailChimp':
                jQuery("label[for='wpsp[template][optInUsername]']").html('Opt In List API Key');
                jQuery("label[for='wpsp[template][optInListID]']").html('Opt In List Name');
                break;
            case 'GetResponse':
                jQuery("label[for='wpsp[template][optInUsername]']").html('Opt In List API Key');
                jQuery("label[for='wpsp[template][optInListID]']").html('Opt In List Campaign Name');
                break;
            case 'aWeber':
            default:
                jQuery("label[for='wpsp[template][optInUsername]']").html('Opt In List Username');
                jQuery("label[for='wpsp[template][optInListID]']").html('Opt In List Name or ID');
                break;
        }
    });
    
    // Opt in form validation
    jQuery("input[name='wpsp[template][optInUsername]']").bind('blur', function() {
        if (jQuery(this).val() == '') {
            alert('Please enter an opt in username for '+jQuery("select[name='wpsp[template][optInProvider]']").val());
        }
    });
    jQuery("input[name='wpsp[template][optInListID]']").bind('blur', function() {
        if (jQuery(this).val() == '') {
            alert('Please enter an opt in list name / list ID for '+jQuery("select[name='wpsp[template][optInProvider]']").val());
        }
    });
    
    // Affiliate Link
    // Check URL is valid
    /*var urlMatch = /https?:\/\/([-\w\.]+)+(:\d+)?(\/([\w/_\.]*(\?\S+)?)?)?/;
    jQuery("input[name='wpsp[template][affiliateLink]']").blur(function(e) {
        if (jQuery(this).val() != '' && !urlMatch.test(jQuery(this).val())) {
            alert('Please enter a valid URL');            
        }
    });*/

})