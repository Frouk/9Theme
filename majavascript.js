function vote(var1, var2) {
    var uped = (document.getElementById("upvoteicon "+ var1).getAttribute("class")=="voteup") ? 0 : -1;
    var downed = (document.getElementById("downvoteicon "+ var1).getAttribute("class")=="votedown") ? 0 : 1;

    if (var2 == 1) {
       document.getElementById("upvoteicon "+ var1).setAttribute("class", "voteupactive");
       document.getElementById("downvoteicon "+ var1).setAttribute("class", "votedown");
       document.getElementById("upvoteicon "+ var1).setAttribute("onclick", "vote("+var1+",0);");
       document.getElementById("downvoteicon "+ var1).setAttribute("onclick", "vote("+var1+",2);");
       document.getElementById("score "+ var1).innerHTML=parseInt(document.getElementById("score "+ var1).innerHTML) + 1 + downed;
    }else if (var2 == 2){
       document.getElementById("upvoteicon " + var1).setAttribute("class", "voteup");
       document.getElementById("downvoteicon " + var1).setAttribute("class", "votedownactive");
       document.getElementById("upvoteicon " + var1).setAttribute("onclick", "vote("+var1+",1);");
       document.getElementById("downvoteicon " + var1).setAttribute("onclick", "vote("+var1+",0);");
       document.getElementById("score " + var1).innerHTML=parseInt(document.getElementById("score "+ var1).innerHTML) - 1 + uped;
    }else{
       document.getElementById("score " + var1).innerHTML=parseInt(document.getElementById("score "+ var1).innerHTML) + uped + downed;
       document.getElementById("upvoteicon " + var1).setAttribute("class", "voteup");
       document.getElementById("downvoteicon " + var1).setAttribute("class", "votedown");
       document.getElementById("upvoteicon " + var1).setAttribute("onclick", "vote("+var1+",1);");
       document.getElementById("downvoteicon " + var1).setAttribute("onclick", "vote("+var1+",2);");
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

   jQuery('div#side-menu-link').on('click', function(e){
       $('#full-screen-menu').fadeIn(500);

       $('#sidebar').insertAfter('#bot_full_screen');
       $('#usercrap').insertAfter('#bot_full_screen');

       e.preventDefault();
   });

   jQuery('div#top-right-button').on('click', function(e){
        $('#sidebar').insertAfter('#sidebar-dummy');
        $('#usercrap').insertAfter('#menuz');

       $('#full-screen-menu').hide();
       e.preventDefault();
   });
});

function videoFull(item) {
	//⊞ ∷ ⧉ ⊠
	var docelem = item.parentElement.childNodes[1];
	 if (docelem.requestFullscreen) {
		    docelem.requestFullscreen();
		}
		else if (docelem.mozRequestFullScreen) {
		    docelem.mozRequestFullScreen();
		}
		else if (docelem.webkitRequestFullscreen) {
		    docelem.webkitRequestFullscreen();
		}
		else if (docelem.msRequestFullscreen) {
		    docelem.msRequestFullscreen();
		}
}

function onVideoReady(mediaPlayer) {
	mediaPlayer.controls = false;

    mediaPlayer.addEventListener('play', function() {
            mediaPlayer.parentElement.childNodes[4].style.display = "none";
    }, false);
}

function togglePlayPause(herrow) {
    herrow = herrow.childNodes[1];
	if (herrow.paused || herrow.ended) {
		//herrow.parentElement.childNodes[4].style.display = "none";
		herrow.play();
	} else {
		herrow.parentElement.childNodes[4].style.display = "inherit";
		herrow.pause();
	}

    // if user clicks play button most likely has auto play turned off,
    // thus it's logical to stop video from playing when he scrolls away.
    herrow.loop = false;
    herrow.addEventListener('ended', function() {
		this.currentTime = 0;
        if (isScrolledIntoView(this)) {
            this.play();
        } else {
            this.parentElement.childNodes[4].style.display = "inherit";
            this.pause();
        }
	}, false);

}

function isScrolledIntoView(el) {
    var elemTop = el.getBoundingClientRect().top;
    var elemBottom = el.getBoundingClientRect().bottom;
    var isVisible = (elemTop >= 0) && (elemBottom <= window.innerHeight);

    return isVisible;
}

function nextImage(event, element, change) {
    event.preventDefault();

    var currentImage = parseInt(element.parentElement.dataset.currentimage);
    var lastImage = parseInt(element.parentElement.dataset.lastimage);

    element.parentElement.getElementsByTagName('img')[currentImage - 1].style.display = "none";

    currentImage = currentImage + change;

    if (currentImage < 1) {
        currentImage = lastImage;
    } else if (currentImage > lastImage) {
        currentImage = 1;
    }
    element.parentElement.getElementsByTagName('img')[currentImage - 1].style.display = "inherit";
    element.parentElement.setAttribute("data-currentimage", currentImage);
    element.parentElement.getElementsByTagName("span")[0].innerHTML = currentImage + "/" + lastImage;
}
