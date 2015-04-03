 function my_js_function() 
{
     jQuery.ajax({
     url: my_ajax_script.ajaxurl,
     data: ({action : 'domyshit'}),
     success: function() {
      //Do stuff here
     }
     });
}

jQuery(document).ready(function($) {

    // Show the login dialog box on click
    jQuery('a#show_login').on('click', function(e){
        jQuery('body').prepend('<div class="login_overlay"></div>');
        jQuery('#popup').fadeIn(500);
        jQuery('div.login_overlay, form#login a.close').on('click', function(){
           jQuery('div.login_overlay').remove();
            jQuery('#popup').hide();
        });
        e.preventDefault();
    });
});
