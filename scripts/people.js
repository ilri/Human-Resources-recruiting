$(document).ready(function() {

	// prevent the click from downloading the video
//	$('div#video a#featured').click(function(event) {
//		event.preventDefault();
//	});

	// for the "featured" videos section
	$('a#featured').each(function() {
		var video = $(this).attr("href");

		flashembed(this, "player_flv_maxi.swf", {
			flv: video, //relative to player!
			showplayer: 'autohide', // (autohide maxi player "play" button)
			showloading: 'never', // (hide maxi player loading text)
			margin: '0', // (hide maxi player margin)
			wmmode: 'opaque'
		});
	});

	// for all the "person" interviews
	$('.person .vid').each(function() {
		var name = $(this).attr('id').split(/\s+/)[0];
		var video = $(this).attr("href");
		var startImage = 'images/people/'+name+'_play.png';
		
		flashembed(this, "player_flv_maxi.swf", {
			flv: video, //relative to player!
			showplayer: 'never', // (hide maxi player "play" button)
			showloading: 'never', // (hide maxi player loading text)
			margin: '0', // (hide maxi player margin)
			startimage: startImage, // (show a "start" image before playing)
			wmmode: 'opaque'
		});
	});
});
