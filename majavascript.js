 var classname = document.getElementsByClassName("voteup");

    var myFunction = function() {
        alert("called");
        jQuery.ajax({
			 url: my_ajax_script.ajaxurl,
			 data: ({action : 'domyshit'}),
			 success: function() {
			  //Do stuff here
			 }
		});
    };

    for(var i=0;i<classname.length;i++){
        classname[i].addEventListener('click', myFunction, false);
    }


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
