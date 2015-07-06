function vote(var1,var2)
{

    if (var2==1){
       document.getElementById("upvoteicon "+ var1).setAttribute("class", "voteupactive");
       document.getElementById("downvoteicon "+ var1).setAttribute("class", "votedown");
       document.getElementById("upvoteicon "+ var1).setAttribute("onclick", "vote("+var1+",0);");
       document.getElementById("downvoteicon "+ var1).setAttribute("onclick", "vote("+var1+",2);");
       document.getElementById("score "+ var1).innerHTML=parseInt(document.getElementById("score "+ var1).innerHTML)+1;
    }else if (var2==2){
       document.getElementById("upvoteicon "+ var1).setAttribute("class", "voteup");
       document.getElementById("downvoteicon "+ var1).setAttribute("class", "votedownactive");
       document.getElementById("upvoteicon "+ var1).setAttribute("onclick", "vote("+var1+",1);");
       document.getElementById("downvoteicon "+ var1).setAttribute("onclick", "vote("+var1+",0);");
       document.getElementById("score "+ var1).innerHTML=parseInt(document.getElementById("score "+ var1).innerHTML)-1;
    }else{
       var upordown=( document.getElementById("upvoteicon "+ var1).getAttribute("class")=="voteup") ? 1 : -1;
       document.getElementById("score "+ var1).innerHTML=parseInt(document.getElementById("score "+ var1).innerHTML)+upordown;
       document.getElementById("upvoteicon "+ var1).setAttribute("class", "voteup");
       document.getElementById("downvoteicon "+ var1).setAttribute("class", "votedown");
       document.getElementById("upvoteicon "+ var1).setAttribute("onclick", "vote("+var1+",1);");
       document.getElementById("downvoteicon "+ var1).setAttribute("onclick", "vote("+var1+",2);");
    }

    jQuery.ajax({
    url: my_ajax_script.ajaxurl,
    data: ({action : 'vote',para1:var1,para2:var2}),
    success: function() {
     //jQuery change color
    }
    });
}

jQuery(document).ready(function($) {

   // Show the login dialog box on click
   jQuery('a#show_login').on('click', function(e){
     jQuery('div.login_overlay').remove();
     jQuery('#popupregister').hide();
       jQuery('body').prepend('<div class="login_overlay"></div>');
       jQuery('#popuplogin').fadeIn(500);
       jQuery('div.login_overlay, form#login a.close').on('click', function(){
          jQuery('div.login_overlay').remove();
           jQuery('#popuplogin').hide();
       });
       e.preventDefault();
   });
 jQuery('a#show_register').on('click', function(e){
   jQuery('div.login_overlay').remove();
   jQuery('#popuplogin').hide();
       jQuery('body').prepend('<div class="login_overlay"></div>');
       jQuery('#popupregister').fadeIn(500);
       jQuery('div.login_overlay, form#register a.close').on('click', function(){
          jQuery('div.login_overlay').remove();
           jQuery('#popupregister').hide();
       });
       e.preventDefault();
   });
   jQuery('a#show_upload').on('click', function(e){
      jQuery('div.login_overlay').remove();
      jQuery('#popupposturl').hide();
       jQuery('body').prepend('<div class="login_overlay"></div>');
       jQuery('#popuppost').fadeIn(500);
       jQuery('div.login_overlay, form#PostUpload a.close').on('click', function(){
          jQuery('div.login_overlay').remove();
           jQuery('#popuppost').hide();
       });
       e.preventDefault();
   });
   jQuery('a#show_upload_url').on('click', function(e){
     jQuery('div.login_overlay').remove();
     jQuery('#popuppost').hide();
       jQuery('body').prepend('<div class="login_overlay"></div>');
       jQuery('#popupposturl').fadeIn(500);
       jQuery('div.login_overlay, form#PostUploadUrl a.close').on('click', function(){
          jQuery('div.login_overlay').remove();
           jQuery('#popupposturl').hide();
       });
       e.preventDefault();
   });
});
