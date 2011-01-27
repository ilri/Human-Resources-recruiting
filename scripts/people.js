$(window).load(function() {

	var $featuredPlayer = flowplayer("featured","videos/flowplayer-3.2.4.swf", {
		clip: {
			coverImage: { url: 'videos/jemimah_n.png', scaling: 'orig' },
			onFinish: function() {
				this.unload();
			},
			onUpdate: function() {
				this.unload();
			}
		}
	});

	// for all the video interviews
	$('.person .video').each(function() {
		
		flowplayer(this, "videos/flowplayer-3.2.4.swf", {
			plugins: {
				controls: null
			},

			clip: {
				autoPlay: true,
				onFinish: function() {
					this.unload();
				}
			}
		});
	});

	// for all the audio interviews
	$('.person .audio').each(function() {
		
		// grab the thumbnail path from the CSS background-image
		// returns something like url("http://example.com/file.png")
		// or url(http://example.com/file.png) if in a Webkit-based browser
		var thumbnail = $(this).css('background-image');
		// cheap regex to extract the thumbnail's URL
		thumbnail = thumbnail.match(/http.*png/i);

		flowplayer(this, "videos/flowplayer-3.2.4.swf", {
			plugins: {
				controls: {
					all: false,
					play: true,
					scrubber: true
				}
			},

			clip: {
				autoPlay: true,
				coverImage: { url: thumbnail, scaling: "orig" },
				onFinish: function() {
					this.unload();
				}
			}
		});
	});

	function swapPeople(x) {
		// store the old values
		var name = featured[0]['name'];
		var description = featured[0]['description'];
		var startimage = featured[0]['startimage'];
		var video = featured[0]['media'];
		var image = featured[0]['image'];

		if($featuredPlayer.isLoaded()) {
			$featuredPlayer.getClip().update({url: featured[x]['media'], coverImage: { url: featured[x]['startimage'], scaling: "orig" }});
		}
		else {
			$featuredPlayer.getClip(0).update({url: featured[x]['media']});
		}

		// replace the values in the DOM
		$('#crowdMemberName').html(featured[x]['name'].toLowerCase());
		$('#crowdMemberDescription').html(featured[x]['description']);
		$('#mainFeature img.person').attr('src',featured[x]['image']).attr('title',featured[x]['name']).attr('alt',featured[x]['name']);
		$('#featured').attr('href',featured[x]['media']);
		$('#featured').css('background-image','url('+featured[x]['startimage']+')');

		// swap the featured person's image out, into the "small"
		$('#features img.person'+x).attr('src',image).attr('title',name).attr('alt',name);

		// swap the values in the array (x -> 0, 0 -> x)
		featured[0]['name'] = featured[x]['name'];
		featured[0]['description'] = featured[x]['description'];
		featured[0]['startimage'] = featured[x]['startimage'];
		featured[0]['media'] = featured[x]['media'];
		featured[0]['image'] = featured[x]['image'];

		featured[x]['name'] = name;
		featured[x]['description'] = description;
		featured[x]['startimage'] = startimage;
		featured[x]['media'] = video;
		featured[x]['image'] = image;
	}

	// swap featured people
	$('#features img.person1').live('click', function (event) {
		// disable normal link behaviour
		event.preventDefault();

		swapPeople(1);
	});

	// swap featured people
	$('#features img.person2').live('click', function (event) {
		// disable normal link behaviour
		event.preventDefault();

		swapPeople(2);
	});

	// select the black and white photos, make them ready for flipping
	$('div.person div.img:not(:has(a))').click(function () {
		var $elem = $(this);

		// check if the element is already "flipped"
		if($elem.data('flipped'))
		{
			$elem.revertFlip(); // call the built-in revertFlip function
			$elem.removeClass('text');
			$elem.data('flipped',false)
		}
		else
		{
			$elem.flip({
				direction:'lr',
				speed: 100,
				color: '#9f9f9f',
				onBefore: function(){
					$elem.addClass('text');
					$elem.html($elem.siblings('.currently').html());
				}
			});

			$elem.data('flipped',true);
		}
	});

//	function flip_person($person) {
//		var $elem = $(this);
//
//		// check if the element is already "flipped"
//		if($elem.data('flipped'))
//		{
//			$elem.revertFlip(); // call the built-in revertFlip function
//			$elem.removeClass('text');
//			$elem.data('flipped',false)
//		}
//		else
//		{
//			$elem.flip({
//				direction:'lr',
//				speed: 100,
//				color: '#9f9f9f',
//				onBefore: function(){
//					$elem.addClass('text');
//					$elem.html($elem.siblings('.currently').html());
//				}
//			});
//
//			$elem.data('flipped',true);
//		}
//	});

	// a simple function to replace one div with another
//	function replaceDiv($div1, $div2) {
//		$div1.fadeOut(1, function () {
//			$div1 = $div1.replaceWith($div2);
//			$div2.fadeIn(200);
//		});
//	}

	$(".scrollable").scrollable({
		keyboard: false, //disable keyboard navigation
		items: "#featuredItems"
	});

	// for the peopleFacts overlay "tabs"
	$("#peopleFactsContent > div").hide();
	$("#peopleFactsContent > div").eq(0).show();
	$("#peopleFactsButtons ul li > a").click(function(event){
		event.preventDefault();

		// hide all content when switching "tabs"
		$("#peopleFactsContent > div").hide();
		// remove the "current" class from the old tab
		$(".current","#peopleFactsButtons ul").removeClass("current");
		$(this).addClass("current");
		// switch "tabs" based on the href target of the current element
		var content = $(this).attr("href");
		$(content).show();
	});

	// a function for scrolling to certain "features"
	var scrollableApi = $(".scrollable").data("scrollable");
	function scrollFeatures($i) {
		// check to see if the featured video is playing.
		// if it is, then pause it an unload it because it
		// causes jerkiness when scrolling, and also flash
		// behaves strangely when hidden (z-indexes don't help)
		if( $featuredPlayer.isPlaying() ) {
			$featuredPlayer.pause(); // pause the video
			scrollableApi.seekTo($i);
			setTimeout(function () { $featuredPlayer.unload(); }, 1000);
		}
		else {
			scrollableApi.seekTo($i);
		}
	}

	// switch to peopleFacts feature
	$("#peopleFactsButton").live('click', function (event){
		event.preventDefault();
		scrollFeatures(1); // scroll to the next div
	});

	// scroll to the currentJobs feature
	$("#ilriJobsButton").live('click', function (event){
		event.preventDefault();
		scrollFeatures(2);
	});

	// scroll back to the videos feature
	$(".back","#featuredItems").live('click', function (event){
		event.preventDefault();
		scrollFeatures(0);
	});

	// to scroll to anchors on the same page (instead of the default jump)
	function goToByScroll(id){
     	$('html,body').animate({scrollTop: $("#"+id).offset().top-70},'slow');
	}

	// for the "current" jobs link at the top
	$("a[href='#feature']").live('click', function (event){
		event.preventDefault();
		goToByScroll("feature");
		setTimeout(function () { scrollFeatures(2); }, 500);
	});

});
