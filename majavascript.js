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
