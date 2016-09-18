$(document).ready(function($) {

   // Show the login dialog box on click
    $('a#show_login').on('click', function(e){
        $('div.login_overlay').remove();
        $('#popupregister').hide();
        $('body').prepend('<div class="login_overlay"></div>');
        $('#popuplogin').fadeIn(500);
        jQuery('div.login_overlay, form#login a.close').on('click', function(){
            $('div.login_overlay').remove();
            $('#popuplogin').hide();
        });
        e.preventDefault();
   });

    $('a#show_register').on('click', function(e){
        $('div.login_overlay').remove();
        $('#popuplogin').hide();
        $('body').prepend('<div class="login_overlay"></div>');
        $('#popupregister').fadeIn(500);
        jQuery('div.login_overlay, form#register a.close').on('click', function(){
            $('div.login_overlay').remove();
            $('#popupregister').hide();
        });
        e.preventDefault();
    });

    $('a#show_upload').on('click', function(e){
        $('div.login_overlay').remove();
        $('#popupposturl').hide();
        $('body').prepend('<div class="login_overlay"></div>');
        $('#popuppost').fadeIn(500);
        jQuery('div.login_overlay, form#PostUpload a.close').on('click', function(){
            $('div.login_overlay').remove();
            $('#popuppost').hide();
        });
        e.preventDefault();
    });

    $('a#show_upload_url').on('click', function(e){
        $('div.login_overlay').remove();
        $('#popuppost').hide();
        $('body').prepend('<div class="login_overlay"></div>');
        $('#popupposturl').fadeIn(500);
        jQuery('div.login_overlay, form#PostUploadUrl a.close').on('click', function(){
            $('div.login_overlay').remove();
            $('#popupposturl').hide();
        });
        e.preventDefault();
   });

   $('div#side-menu-link').on('click', function(e){
       $('#full-screen-menu').fadeIn(500);
       $('#sidebar').insertAfter('#bot_full_screen');
       $('#usercrap').insertAfter('#bot_full_screen');
       e.preventDefault();
   });

   $('div#top-right-button').on('click', function(e){
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
	} else if (docelem.mozRequestFullScreen) {
		docelem.mozRequestFullScreen();
	} else if (docelem.webkitRequestFullscreen) {
		docelem.webkitRequestFullscreen();
	} else if (docelem.msRequestFullscreen) {
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
    event.stopPropagation();
    var currentImage = parseInt(element.dataset.currentimage);
    var lastImage = parseInt(element.dataset.lastimage);

    element.getElementsByTagName('img')[currentImage - 1].style.display = "none";

    currentImage = currentImage + change;

    if (currentImage < 1) {
        currentImage = lastImage;
    } else if (currentImage > lastImage) {
        currentImage = 1;
    }
    element.getElementsByTagName('img')[currentImage - 1].style.display = "inherit";
    element.setAttribute("data-currentimage", currentImage);
    element.getElementsByTagName("span")[0].innerHTML = currentImage + "/" + lastImage;
}
