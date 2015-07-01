 function vote($var1,$var2)
{
     jQuery.ajax({
     url: my_ajax_script.ajaxurl,
     data: ({action : 'vote',para1:$var1,para2:$var2}),
     success: function() {
      //jQuery change color
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
