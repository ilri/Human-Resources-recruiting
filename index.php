<!doctype html>
<html>
<head>
	<title>ILRI - International Livestock Research Institutute</title>
	<?php
		require_once('includes/head.php');
		require_once('includes/functions.php');
		$pageref = $_SERVER['PHP_SELF']; // are we index.php?
	?>
</head>
<body>

<div id="outer">
<div id="header">
<div id="banner"><a href="/" title="International Livestock Research Institute"><img src="images/ilriBannerbioin.jpg" alt="International Livestock Research Institute" /></a></div>
</div> <!-- //#header -->

<div id="inner">
<noscript>
<div id="no-js">
	This website is so much more fun if you have JavaScript enabled!
</div>
</noscript>
<div style="width: 960px; height: 16px; background-color: lightgrey; margin-left: -88px; padding-top: 4px; font-size: 12px;">
<a href="#feature" style="margin-left: 50px; padding-top: 8px; color: #333;">Click here to see some current ILRI jobs</a>
</div>
<div id="peopleLeftMenu">
<img src="images/ilricrowd_left.png" />
<?php
	// Check if we're in "audio-only" mode and change the page behavior accordingly
	if(!empty($_POST['media']) && $_POST['media'] == 'audio') {
		// set the media type to audio
		$media_type = 'audio';
	}
	else if(!empty($_POST['media']) && $_POST['media'] == 'video') {
		// set the media type to video
		$media_type = 'video';
	}
	else $media_type = 'video'; // otherwise just default to 'video' mode
?>
<form id="sortByLocation" class="sortby first" action="<?php echo $pageref; ?>" method="post">
	<input type="hidden" name="sortby" value="location" />
	<?php // if we're in audio mode, set the media type of the button to audio so we maintain media mode when switching sort criteria ?>
	<input type="hidden" name="media" value="<?php echo $media_type;?>" />
	<input type="image" name="submit" src="images/sort_by_location.png" />
</form>
<form id="sortByJobTitle" class="sortby" action="<?php echo $pageref; ?>" method="post">
	<input type="hidden" name="sortby" value="jobtitle" />
	<input type="hidden" name="media" value="<?php echo $media_type;?>" />
	<input type="image" name="submit" src="images/sort_by_job_title.png" />
</form>
<form id="mediaType" class="sortby" action="<?php echo $pageref; ?>" method="post">
<?php
	if($media_type == 'audio') {
		echo '	<input type="hidden" name="media" value="video" />'."\n";
		echo '	<input type="image" name="submit" src="images/video_only.png" />'."\n";
	}
	else {
		echo '	<input type="hidden" name="media" value="audio" />'."\n";
		echo '	<input type="image" name="submit" src="images/audio_only.png" />'."\n";
	}
?>
</form>
</div> <!-- //#peopleLeftMenu -->

<img src="images/ilricrowd_right1.png" id="ilricrowdright1" />

<div id="peopleGrid">
<?php
	// create a SimpleXMLObject
	$people_xml_object = simplexml_load_file('people.xml');

	// convert it to an array
	$people = xmlobj2array($people_xml_object->children());

	// simplexml returns a namespace "person" containing several "people" so
	// we reset our people array to this subset
	$people = $people['person'];

	// put "featured" people in their own array
	// there should only be three of them, as we've got three slots in the layout.
	$featured_people = array(); // initialize empty array
	// loop through our people and search for persons who are "featured"
	// when you find one, stick it in the $featured_people array and remove them from people
	foreach($people as $elementnum => $person) {
		if(array_key_exists('featured',$person)) {
			array_push($featured_people, $people[$elementnum]);
			unset($people[$elementnum]);
		}
	}

	// put campuses in their own array
	// there should only be two of them, as we've got two slots in the layout.
	// ... test for the "iscampus" key to see if a person is actually a campus
	$featured_campuses = array(); // initialize empty array
	foreach($people as $elementnum => $person) {
		if(array_key_exists('iscampus',$person)) {
			array_push($featured_campuses, $people[$elementnum]);
			unset($people[$elementnum]);
		}
	}

	// put "New Ways of Working" people in their own array
	// there should only be eight of them, as we've got eight slots in the layout.
	$newways_people = array(); // initialize empty array
	foreach($people as $elementnum => $person) {
		if(array_key_exists('newways',$person)) {
			array_push($newways_people, $people[$elementnum]);
			unset($people[$elementnum]);
		}
	}

	// determine how to sort our people!
	// first see if a "sortby" value exists
	if(!empty($_POST['sortby'])) {
		$sortby = $_POST['sortby'];

		// check for a sane sort value, otherwise just use the order in the XML file
		if($sortby == 'jobtitle') $people = sort_subval($people,'jobtitle');
		else if($sortby == 'location') $people = sort_subval($people,'location');
		else {
			unset($sortby);	// unset 'sortby' and assume the sortby value is not sane.
							// sort the people using the order they appear in the XML file
		}
	}

	$count = 0;
	if(isset($people) && !empty($people)) {
		foreach($people as $person) {

			// if this person is a rep, take note
			if($person['jobtitle'] == 'Regional Representative') $rep = true;
			else $rep = false;

			if($count == 2) {
				echo '<img src="images/ilricrowd_text.png" id="ilricrowdright2" />';
			}

			// draw the "featured" section
			if($count == 4) {
				echo '<div class="scrollable">';
				echo '<div id="featuredItems">';
				echo '<div id="feature">';
				echo '	<div id="featureLeft">';
				echo '		<div id="featureLeftTop">';
				echo '			<div id="featuredCampuses">';
				// print the campus tours
				for($x = 0; $x < 2; $x++) {
					echo '				<div class="campus campus'.$x.'">'."\n";
					echo '					<img class="campus campus'.$x.'" src="'.$featured_campuses[$x]['image'].'" title="'.$featured_campuses[$x]['name'].'" alt="'.$featured_campuses[$x]['name'].'" height="57" width="50" />'."\n";
					echo '					<span class="name">'.strtolower($featured_campuses[$x]['name']).'</span>'."\n";
					echo '				</div>';
				}
				echo '			</div>';
				echo '			<div id="featuredCampusesDesc">';
				echo '				<h2>what is it like to work at ilri?</h2>';
				echo '				<span class="text">click icons to take a campus tour and find out</span>';
				echo '			</div>';
				echo '		</div>';
				echo '		<div id="featureLeftTopMeta">';
				echo '			<div id="crowdMember" style="font-size: 14px; color: #6d6d6d; margin-top: 20px; margin-left: 24px; height: 17px; width: 172px; float: left;">featured video:</div>';
				echo '			<div id="crowdMemberName" class="feature0" style="font-size: 20px; font-weight: 600; color: #6d6d6d; margin-top: 15px; height: 22px; width: 196px; float: left; text-align: right;">'.strtolower($featured_people[0]['name']).'</div>';
				echo '			<div id="crowdMemberDescription" style="font-size: 13px; color: #4d4d4d; height: 25px; width: 370px; margin-top: 10px; margin-left: 24px; text-align: left; float: left;">'.strtolower($featured_people[0]['description']).'</div>';
				echo '		</div>';
				echo '		<div id="featureLeftBottom">';
				echo '			<a id="featured" href="'.$featured_people[0][$media_type].'" style="background-image: url('.$featured_people[0]['startimage'].');"><img src="images/play.png" class="play" height="55" width="55" /></a>';
				echo '		</div>';
				echo '	</div>';
				echo '	<div id="featureRight">';
				if(isset($featured_people) && !empty($featured_people)) {
					// show 1 of our "featured" people big
					for($x = 0; $x < 1; $x++) {
						echo '			<div id="mainFeature">';
						echo '				<img class="person" src="'.$featured_people[$x]['image'].'" title="'.$featured_people[$x]['name'].'" alt="'.$featured_people[$x]['name'].'" height="150" width="130" />'."\n";
						echo '			</div>';
					}
				echo '			<div id="otherFeatures">';
				echo '				<div id="featuredStaff">';
				echo '				<span class="person">click icons for more featured staff</span>'."\n";
					// show the other 2 small (plus one placeholder to store people temporarily)
					for($x = 1; $x <= 3; $x++) {
						echo '				<img class="person person'.$x.'" src="'.$featured_people[$x]['image'].'" title="'.$featured_people[$x]['name'].'" alt="'.$featured_people[$x]['name'].'" height="57" width="50" />'."\n";
					}
				echo 	'			</div>';
				echo '			</div>';
				}

				echo '		<div id="featureButtons">';
				echo '		<img src="images/ilri_people_facts.png" title="ILRI people facts" alt="ILRI people facts" class="button first" id="peopleFactsButton" height="30" width="262" />';
				echo '		<a href="#newways" title="new ways of working at ILRI"><img src="images/new_ways.png" title="new ways of working at ILRI" alt="new ways of working at ILRI" class="button" height="30" width="262" /></a>';
				echo '		<img src="images/ilri_jobs_lalign.png" title="ILRI jobs" alt="See some current ILRI jobs" class="button" id="ilriJobsButton" height="30" width="262" />'."\n";
				echo '		</div>';
				echo '	</div>';
				echo '</div>'; //end of #featured
?>
<div id="peopleFacts">
	<h2 class="title">people@ilri</h2>
	<div class="content">
		<div id="peopleFactsButtons">
			<ul>
				<li><a href="#peopleFacts-1" class="current">Specialities</a></li>
				<li><a href="#peopleFacts-2">Countries</a></li>
				<li><a href="#peopleFacts-3">More About Staff</a></li>
			</ul>
		</div>
		<div id="peopleFactsContent">
			<div id="peopleFacts-1">
				<p style="text-align: left; margin: 10px 0 0 10px;">Current ILRI staff specialities include...</p>
				<ul style="margin: 75px 5px 5px 10px; font-size: 13px;">
					<li>Agronomist</li>
					<li>Anthropologist</li>
					<li>Bioinformatician</li>
					<li>Capacity Builder</li>
					<li>Science Communicators</li>
					<li>Computer Scientist</li>
					<li>Disease Modeler</li>
					<li>Ecologist</li>
					<li>Economist</li>
					<li>Epidemiologist</li>
					<li>Geneticist</li>
					<li>Geographer</li>
					<li>GIS Expert</li>
					<li>Human Geographer</li>
					<li>Immunologist</li>
					<li>Knowledge Sharer</li>
					<li>Livestock Systems Scientist</li>
					<li>Statisticians</li>
					<li>Systems Analyst</li>
					<li>Medical Doctor</li>
					<li>Parasitologist</li>
					<li>Social Scientist</li>
					<li>Veterinarian</li>
					<li>Virologist</li>
				</ul>
			</div>
			<div id="peopleFacts-2">
				<p style="text-align: left; margin: 10px 0 0 10px;">Our staff come from 35 countries...</p>
				<ul style="margin: 75px 5px 5px 10px; font-size: 13px;">
					<li>Australia</li>
					<li>Barbados</li>
					<li>Belgium</li>
					<li>Benin</li>
					<li>Cameroon</li>
					<li>Canada</li>
					<li>China</li>
					<li>Colombia</li>
					<li>Costa Rica</li>
					<li>Denmark</li>
					<li>Ethiopia</li>
					<li>France</li>
					<li>Germany</li>
					<li>Ghana</li>
					<li>Haiti</li>
					<li>India</li>
					<li>Ireland</li>
					<li>Japan</li>
					<li>Kenya</li>
					<li>Mozambique</li>
					<li>Netherlands</li>
					<li>New Zealand</li>
					<li>Nigeria</li>
					<li>Philippines</li>
					<li>Senegal</li>
					<li>South Africa</li>
					<li>Sri Lanka</li>
					<li>Sweden</li>
					<li>Taiwan</li>
					<li>Tanzania</li>
					<li>Tunisia</li>
					<li>Uganda</li>
					<li>United Kingdom</li>
					<li>United States of America</li>
					<li>Uruguay</li>
					<li>Zimbabwe</li>
				</ul>
			</div>
			<div id="peopleFacts-3">
				<p style="text-align: left; margin: 140px 0px 0px 10px;">Almost 60% of our international staff are from developing nations.</p>
				<p style="text-align: left; margin: 20px 0px 0px 10px;">35% of our staff are female.</p>
			</div>
		</div>
		<div class="nav">
			<div style="height: 30px; font-size: 10px;"><a href="#" class="back"><img src="images/arrow_left.jpg" style="display: inline; vertical-align: middle;" /> Back to the featured videos</a></div>
		</div> <!-- //div.nav -->
	</div> <!-- //div.content -->
</div> <!-- //div#peopleFacts -->
<?php
				echo '<div id="jobs">'."\n";
				echo '	<h2 class="title">jobs@ilri</h2>'."\n";
				echo '	<div class="content">'."\n";
				print_current_jobs();
				echo '		<div class="nav">
			<span style="width: 50%; float: left; text-align: left;">
				<a href="#" class="back"><img src="images/arrow_left.jpg" style="display: inline; vertical-align: middle;" /> Back to the featured videos</a>
			</span>
			<span style="width: 50%; float: right; text-align: right;">
				<a href="http://ilri.org/aggregator/sources/7" title="More jobs">More <img src="images/arrow_right.jpg" style="display: inline; vertical-align: middle;" /></a>
			</span>
		</div> <!-- //div.nav -->';
				echo '	</div>'."\n";
				echo '</div>'; //end of #jobs
				echo '</div>'; //end of .items
				echo '</div>'; //end of .scrollable
			}
			
			if($rep) $class = "person rep";
			else $class = "person";

			echo '<div class="'.$class.'">'."\n";
			echo '	<div class="img">'."\n";

			// if the current person has audio or video, insert that instead of their picture
			if( isset($person['video']) || isset($person['audio']) ) {
				echo '		<a class="'.$media_type.'" style="background-image: url('.$person['image'].');" href="'.$person[$media_type].'"><img src="images/play.png" class="play" title="'.$person['name'].'" alt="'.$person['name'].'" height="25" width="25" /></a>'."\n";
			}
			else {
				if(isset($person['link'])) {
					echo '		<a href="'.$person['link'].'" title="Azizi Biobank on Facebook">'."\n";
					echo '			<img src="'.$person['image'].'" title="'.$person['name'].'" alt="'.$person['name'].'" height="150" width="130" />'."\n";
					echo '		</a>'."\n";
				}
				else {
					echo '		<img class="person" src="'.$person['image'].'" title="'.$person['name'].'" alt="'.$person['name'].'" height="150" width="130" />'."\n";
				}
			}
			echo '	</div>'."\n";
			echo '	<div class="currently"><p>Currently...</p><br />'.$person['currently']."</div>\n";

			if($rep) $class = "name rep";
			else $class = "name";

			echo '	<span class="'.$class.'">'.$person['name'].'</span>'."\n";

			if($sortby == 'jobtitle') {
				echo '	<span class="jobtitle">'.$person['jobtitle'].'</span>'."\n";
				if($rep)
					echo '	<span class="region">'.$person['region'].'</span>'."\n";
				echo '</div>'."\n";
			}
			else if($sortby == 'location') {
				echo '	<span class="location"><img title="ILRI campus" alt="Home icon" src="images/home.png" />'.$person['location'].'</span>'."\n";
				echo '</div>'."\n";
			}
			else {
				echo '	<span class="nationality"><img title="Country of Origin" alt="Flag icon" src="images/flag.png" />'.$person['nationality'].'</span>'."\n";
				echo '</div>'."\n";
			}

			$count++;
		}
	}
?>
</div>
<div id="newWays">
	<div class="row row1">
		<div class="heading">
			<h2><a name="newways">new ways of working - what suits you?</a></h2>
		</div>
		<div id="newerWays" style="float: left; margin-top: 25px; height: 75px;">
			<div style="width: 262px; height: 75px;"><a href="http://ilri.org/HumanResources" title="ILRI human resources"><img src="images/more_info.png" /></a></div>
		</div>
		<div class="scientists">
			An increasing number of scientists are employed through innovative arrangements such as joint appointments, sponsored positions and sabbaticals from international institutions.
		</div>
	</div>
<?php
		// print out two rows of "New Ways" people (eight people total)
		foreach($newways_people as $elementnum => $person) {
			if($elementnum == 0) {
				echo '<div class="row">'."\n";
				echo '	<div class="heading"></div>'."\n";
			}
			echo '<div class="person">'."\n";
			if($elementnum == 5) { // element 5 = Eric Fevre
				echo '	<a class="'.$media_type.'" style="background-image: url('.$person['image'].');" href="'.$person[$media_type].'" title="'.$person['name'].'"><img src="images/play.png" class="play" alt="Play icon" height="25" width="25" /></a>'."\n";
				echo '	<span class="name">'.$person['name'].'</span>'."\n";
				echo '</div>'."\n";
			}
			else {
				echo '	<div class="img">'."\n";
				echo '		<img class="person" src="'.$person['image'].'" height="150" width="130" title="'.$person['name'].'" alt="'.$person['name'].'" />'."\n";
				echo '	</div>'."\n";
				echo '	<div class="biography">'.$person['specialty'].'<br/><br/>'.$person['organization'].'</div>'."\n";
				echo '	<span class="name">'.$person['name'].'</span>'."\n";
				echo '</div>'."\n";
			}
			if($elementnum == 3) { //element 3 is person 4, end row 1 and print row 2!
				echo '</div>'."\n";
				echo '<div class="row">'."\n";
				echo '	<div class="heading"></div>'."\n";
			}
			if($elementnum == 7) { //element 7 is person 8, end row 2
				echo '</div>'."\n";
			}
		}
?>
</div>
<br style="clear: both;"/>
</div>
<a href="http://ilri.org/jobs" title="Jobs page on ilri.org"><img src="images/ilri_jobs_ralign.png" height="30" width="262" alt="Check out jobs on ilri.org" class="button first" /></a>
<a href="#"><img src="images/back_to_top.png" class="button" /></a>

<div id="footer">
	<div id="footer-inner">
		<p><a href="http://www.ilri.org/ContactUs">Contact us</a> | <a href="http://www.ilri.org/CopyRight">Copyright and permissions</a> | <a href="http://www.ilri.org/Search">Search</a> | <a href="http://www.ilri.org/Newsfeeds">Subscribe</a><br>
		&copy; International Livestock Research Institute (ILRI)</p>
	</div>
</div>
</div> <!-- //#inner -->

</div> <!-- //#outer -->

<script type="text/javascript" src="scripts/jquery-1.5.2.min.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.8.11.min.js"></script>
<script type="text/javascript" src="scripts/jquery.flip.min.js"></script>
<script type="text/javascript" src="scripts/flowplayer-3.2.6.min.js"></script>
<script type="text/javascript" src="scripts/jquery.tools.min.js"></script>
<script type="text/javascript" src="scripts/people.js"></script>
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-10691096-11']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
<script type="text/javascript">
<? 
	// print out our featured people's information so we can swap it in javascript
	echo 'var featured_people = new Array();'."\n";
	for($x = 0; $x <3; $x++) {
		echo "featured_people[$x] = new Array();\n";
		echo "featured_people[$x]['image'] = \"".$featured_people[$x]['image']."\";\n";
		echo "featured_people[$x]['name'] = \"".strtolower($featured_people[$x]['name'])."\";\n";
		echo "featured_people[$x]['startimage'] = \"".$featured_people[$x]['startimage']."\";\n";
		echo "featured_people[$x]['description'] = \"".$featured_people[$x]['description']."\";\n";
		// use keyword 'media' on client side so that we don't have to do anything special
		// when swapping our featured people in javascript (just swap "media", whether it's audio or video)
		echo "featured_people[$x]['media'] = \"";echo $featured_people[$x][$media_type];echo "\";\n";
		// person3 is where we put the currently-loaded person when we load a campus video, as there are only two "staff slots" normally
		echo "featured_people['person3'] = new Array();\n";
	}

	// print out our featured campus' information so we can swap it in javascript
	echo 'var featured_campuses = new Array();'."\n";
	for($x = 0; $x <2; $x++) {
		echo "featured_campuses[$x] = new Array();\n";
		echo "featured_campuses[$x]['image'] = \"".$featured_campuses[$x]['image']."\";\n";
		echo "featured_campuses[$x]['name'] = \"".strtolower($featured_campuses[$x]['name'])."\";\n";
		echo "featured_campuses[$x]['startimage'] = \"".$featured_campuses[$x]['startimage']."\";\n";
		echo "featured_campuses[$x]['description'] = \"".$featured_campuses[$x]['description']."\";\n";
		// use keyword 'media' on client side so that we don't have to do anything special
		// when swapping our featured campuses in javascript (just swap "media", whether it's audio or video)
		echo "featured_campuses[$x]['media'] = \"";echo $featured_campuses[$x][$media_type];echo "\";\n";

	}
?>
</script>
</body>
</html>
