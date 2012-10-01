/**
* Frontend Javascript Functionality
* 
* @package js
* @author Tim Carr
*/
$(document).ready(function() {    
    var filter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
    
    // Add description CSS to newsletter fields
    $('form.newsletter p input.name').addClass('description');    
    $('form.newsletter p input.email').addClass('description');
    
    $('form.newsletter p input.name').bind('focus', function() {
        if ($(this).val() == 'Name') {
            $(this).val('');
            $(this).removeClass('description');
        }
    });
    $('form.newsletter p input.name').bind('blur', function() {
        if ($(this).val() == '') {
            $(this).val('Name');
            $(this).addClass('description');
        }
    });
    
    $('form.newsletter p input.email').bind('focus', function() {
        if ($(this).val() == 'Email') {
            $(this).val('');
            $(this).removeClass('description');
        }
    });
    $('form.newsletter p input.email').bind('blur', function() {
        if ($(this).val() == '') {
            $(this).val('Email');
            $(this).addClass('description');
        }
    });
    
    // Form Validation
    $('form.newsletter').bind('submit', function(e) {
        if ($('input.name', this).val() == '' || $('input.name', this).val() == 'Name') {
            e.preventDefault();
            alert('Please enter a name.');
            return false;
        }
        if ($('input.email', this).val() == '' || $('input.email', this).val() == 'Email') {
            e.preventDefault();
            alert('Please enter an email address.');
            return false;
        }
        if (!filter.test($('input.email', this).val())) {
            alert('Please enter a valid email address.');
            return false;
        }
        
        return true;
    });
})