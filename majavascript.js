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
        jQuery('#popuplogin').fadeIn(500);
        jQuery('div.login_overlay, form#login a.close').on('click', function(){
           jQuery('div.login_overlay').remove();
            jQuery('#popuplogin').hide();
        });
        e.preventDefault();
    });
	jQuery('a#show_register').on('click', function(e){
        jQuery('body').prepend('<div class="login_overlay"></div>');
        jQuery('#popupregister').fadeIn(500);
        jQuery('div.login_overlay, form#register a.close').on('click', function(){
           jQuery('div.login_overlay').remove();
            jQuery('#popupregister').hide();
        });
        e.preventDefault();
    });
});
