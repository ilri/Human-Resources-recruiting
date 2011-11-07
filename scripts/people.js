$(window).load(function() {

	var $featuredPlayer = flowplayer("featured","includes/flowplayer-3.2.7.swf", {
		clip: {
			coverImage: { url: 'images/people/jemimah_n_video.jpg', scaling: 'orig' },
			onFinish: function() {
				this.unload();
			},
			onUpdate: function() {
				this.unload();
			}
		}
	});

	// set the feature type on the player element so we can test it later
	// ... "type" is either: person or campus
	$("#featured").data("featureType","person");

	// for all the video interviews
	$('.person .video').each(function() {
		
		flowplayer(this, "includes/flowplayer-3.2.7.swf", {
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
		// returns something like url("http://example.com/file.jpg")
		// or url(http://example.com/file.jpg) if in a Webkit-based browser
		var thumbnail = $(this).css('background-image');
		// cheap regex to extract the thumbnail's URL
		thumbnail = thumbnail.match(/http.*jpg/i);

		flowplayer(this, "includes/flowplayer-3.2.7.swf", {
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

	// swap "featured" videos, both people and campuses
	function swapFeaturedVideo(featureType, x) {
		// check which kind of video is currently loaded in the player (either: campus or person)
		currentFeatureType = $("#featured").data("featureType");

		// if person is currently loaded, and we're loading another person
		if(currentFeatureType == 'person' && featureType == 'person')
		{
			// store the old values
			var name = featured_people[0]['name'];
			var description = featured_people[0]['description'];
			var startimage = featured_people[0]['startimage'];
			var video = featured_people[0]['media'];
			var image = featured_people[0]['image'];

			if($featuredPlayer.isLoaded()) {
				$featuredPlayer.getClip().update({url: featured_people[x]['media'], coverImage: { url: featured_people[x]['startimage'], scaling: "orig" }});
			}
			else {
				$featuredPlayer.getClip(0).update({url: featured_people[x]['media'], coverImage: { url: featured_people[x]['startimage'], scaling: "orig" }});
			}

			// replace the values in the DOM
			$('#crowdMemberName').html(featured_people[x]['name']);
			$('#crowdMemberDescription').html(featured_people[x]['description']);
			$('#mainFeature img.person').attr('src',featured_people[x]['image']).attr('title',featured_people[x]['name']).attr('alt',featured_people[x]['name']);
			$('#featured').attr('href',featured_people[x]['media']);
			$('#featured').css('background-image','url('+featured_people[x]['startimage']+')');

			// make sure we update the "feature type"
			$("#featured").data("featureType","person");

			// swap the featured person's image out, into the "small"
			$('#featuredStaff img.person'+x).attr('src',image).attr('title',name).attr('alt',name);

			// swap the values in the array (x -> 0, 0 -> x)
			featured_people[0]['name'] = featured_people[x]['name'];
			featured_people[0]['description'] = featured_people[x]['description'];
			featured_people[0]['startimage'] = featured_people[x]['startimage'];
			featured_people[0]['media'] = featured_people[x]['media'];
			featured_people[0]['image'] = featured_people[x]['image'];

			featured_people[x]['name'] = name;
			featured_people[x]['description'] = description;
			featured_people[x]['startimage'] = startimage;
			featured_people[x]['media'] = video;
			featured_people[x]['image'] = image;
		}
		// if person is currently loaded, and we're loading a campus
		else if(currentFeatureType == 'person' && featureType == 'campus')
		{
			// save the currently-loaded person's values into the people array as "person3"
			featured_people['person3']['name'] = featured_people[0]['name'];
			featured_people['person3']['description'] = featured_people[0]['description'];
			featured_people['person3']['startimage'] = featured_people[0]['startimage'];
			featured_people['person3']['media'] = featured_people[0]['media'];
			featured_people['person3']['image'] = featured_people[0]['image'];

			if($featuredPlayer.isLoaded()) {
				$featuredPlayer.getClip().update({url: featured_campuses[x]['media'], coverImage: { url: featured_campuses[x]['startimage'], scaling: "orig" }});
			}
			else {
				$featuredPlayer.getClip(0).update({url: featured_campuses[x]['media'], coverImage: { url: featured_campuses[x]['startimage'], scaling: "orig" }});
			}

			// replace the values in the DOM
			$('#crowdMemberName').html(featured_campuses[x]['name']);
			$('#crowdMemberDescription').html(featured_campuses[x]['description']);
			$('#mainFeature img.person').attr('src',featured_campuses[x]['image']).attr('title',featured_campuses[x]['name']).attr('alt',featured_campuses[x]['name']);
			$('#featured').attr('href',featured_campuses[x]['media']);
			$('#featured').css('background-image','url('+featured_campuses[x]['startimage']+')');

			// make sure we update the "feature type"
			$("#featured").data("featureType","campus");

			// swap the featured person's image out into the person3 placeholder
			$('#featuredStaff img.person3').attr('src',featured_people['person3']['image']).attr('title',featured_people['person3']['name']).attr('alt',featured_people['person3']['name']);
			$('#featuredStaff img.person3').css('display','inline')
		}
		// if campus is currently loaded, and we're loading a campus
		else if(currentFeatureType == 'campus' && featureType == 'campus')
		{
			if($featuredPlayer.isLoaded()) {
				$featuredPlayer.getClip().update({url: featured_campuses[x]['media'], coverImage: { url: featured_campuses[x]['startimage'], scaling: "orig" }});
			}
			else {
				$featuredPlayer.getClip(0).update({url: featured_campuses[x]['media']});
			}

			// replace the values in the DOM
			$('#crowdMemberName').html(featured_campuses[x]['name'].toLowerCase());
			$('#crowdMemberDescription').html(featured_campuses[x]['description']);
			$('#mainFeature img.person').attr('src',featured_campuses[x]['image']).attr('title',featured_campuses[x]['name']).attr('alt',featured_campuses[x]['name']);
			$('#featured').attr('href',featured_campuses[x]['media']);
			$('#featured').css('background-image','url('+featured_campuses[x]['startimage']+')');

			// make sure we update the "feature type"
			$("#featured").data("featureType","campus");
		}
		// if campus is currently loaded, and we're loading a person
		else if(currentFeatureType == 'campus' && featureType == 'person')
		{
			// we have to remember to pop person3 from their temp spot after swapping the requested staff member
			// did the user click "person3" or another staff member?
			if(x == 'person3')
			{
				// grab person3's data and stick it back in 0
				featured_people[0]['name'] = featured_people['person3']['name'];
				featured_people[0]['name'] = featured_people['person3']['name'];
				featured_people[0]['description'] = featured_people['person3']['description'];
				featured_people[0]['startimage'] = featured_people['person3']['startimage'];
				featured_people[0]['media'] = featured_people['person3']['media'];
				featured_people[0]['image'] = featured_people['person3']['image'];
			}
			// user didn't click person3, so just swap the "x" person to 0 and then put person3 in x
			else
			{
				// swap the values in the array (x -> 0, 0 -> x)
				featured_people[0]['name'] = featured_people[x]['name'];
				featured_people[0]['description'] = featured_people[x]['description'];
				featured_people[0]['startimage'] = featured_people[x]['startimage'];
				featured_people[0]['media'] = featured_people[x]['media'];
				featured_people[0]['image'] = featured_people[x]['image'];

				// save person3 into person "x" (the one that just got swapped above)
				featured_people[x]['name'] = featured_people['person3']['name'];
				featured_people[x]['description'] = featured_people['person3']['description'];
				featured_people[x]['startimage'] = featured_people['person3']['startimage'];
				featured_people[x]['media'] = featured_people['person3']['media'];
				featured_people[x]['image'] = featured_people['person3']['image'];
			}

			if($featuredPlayer.isLoaded()) {
				$featuredPlayer.getClip().update({url: featured_people[0]['media'], coverImage: { url: featured_people[0]['startimage'], scaling: "orig" }});
			}
			else {
				$featuredPlayer.getClip(0).update({url: featured_people[0]['media']});
			}

			// replace the values in the DOM
			$('#crowdMemberName').html(featured_people[0]['name']);
			$('#crowdMemberDescription').html(featured_people[0]['description']);
			$('#mainFeature img.person').attr('src',featured_people[0]['image']).attr('title',featured_people[0]['name']).attr('alt',featured_people[0]['name']);
			$('#featured').attr('href',featured_people[0]['media']);
			$('#featured').css('background-image','url('+featured_people[0]['startimage']+')');
			// and don't forget to put person3's image back into person "x" (which has just been swapped to person0)
			$('#featuredStaff img.person'+x).attr('src',featured_people[x]['image']).attr('title',featured_people[x]['name']).attr('alt',featured_people[x]['name']);

			// hide person3 again
			$('#featuredStaff img.person3').css('display','none');

			// make sure we update the "feature type"
			$("#featured").data("featureType","person");
		}
	}

	// swap featured people
	$('#featuredStaff img.person1').live('click', function (event) {
		// disable normal link behaviour
		event.preventDefault();

		swapFeaturedVideo("person",1);
	});

	// swap featured people
	$('#featuredStaff img.person2').live('click', function (event) {
		// disable normal link behaviour
		event.preventDefault();

		swapFeaturedVideo("person",2);
	});

	// swap featured people
	$('#featuredStaff img.person3').live('click', function (event) {
		// disable normal link behaviour
		event.preventDefault();

		swapFeaturedVideo("person","person3");
	});

	// swap featured campuses
	$('#featuredCampuses img.campus0').live('click', function (event) {
		// disable normal link behaviour
		event.preventDefault();

		swapFeaturedVideo("campus",0);
	});

	// swap featured campuses
	$('#featuredCampuses img.campus1').live('click', function (event) {
		// disable normal link behaviour
		event.preventDefault();

		swapFeaturedVideo("campus",1);
	});

	// select the black and white photos, and flip them on click
	$('#peopleGrid div.person div.img:not(:has(a))').click(function () {
		var $person = $(this);
		flip_person($person,'.currently'); // flip the photo to show what they are "currently" doing
	});

	$('#newWays div.person div.img:not(:has(a))').click(function () {
		var $person = $(this);
		flip_person($person,'.biography'); // flip the photo to show what their biographical information
	});

	// a generic function to "flip" photos
	// takes a parameter "$element" which is the element to be printed on the "flipped" side
	function flip_person($person,$element) {
		// check if the personent is already "flipped"
		if($person.data('flipped'))
		{
			$person.revertFlip(); // call the built-in revertFlip function
			$person.removeClass('text');
			$person.data('flipped',false)
		}
		else
		{
			$person.flip({
				direction:'lr',
				speed: 100,
				color: '#9f9f9f',
				onBefore: function(){
					$person.addClass('text');
					$person.html($person.siblings($element).html());
				}
			});

			$person.data('flipped',true);
		}
	};

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
