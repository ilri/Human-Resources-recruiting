<!doctype html>
<html>
<head>
	<title>ILRI - International Livestock Research Institutute</title>
	<?php require_once('head_includes.php'); ?>
</head>
<body>

<div id='outer'>
<div id='header'>
<?php require_once('navigation.php'); ?>
<div id="banner"></div>
</div> <!-- //header -->

<div id='inner'>
<div id='aboutus'>
It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using. <a href="#">Testing links</a>.
</div>
<div id="player" class='person'><img src="images/mugshots/2andrew better.png" /></div>

<div class='person'><img src="images/mugshots/steve_k.png" /></div>
<div class='person'><img src="images/mugshots/steve_k.png" /></div>
<div class='person' id="player3"><img src="images/mugshots/2andrew better.png" /></div>
<div class='person'><img src="images/mugshots/steve_k.png" /></div>
<div class='person' id="player2"><img src="images/mugshots/2andrew better.png" /></div>
<div class='person'><img src="images/mugshots/steve_k.png" /></div>
<script type="text/javascript">
	flashembed("player","videos/flayr.swf", {
		movie: 'andrew.flv', //relative to player!
		autoplay: 'true',
		controls: 'hide'
		}
	);

	flashembed("player2","videos/flayr.swf", {
		movie: 'andrew.flv', //relative to player!
		autoplay: 'true',
		controls: 'hide'
		}
	);

	flashembed("player3","videos/flayr.swf", {
		movie: 'andrew.flv', //relative to player!
		autoplay: 'true',
		controls: 'hide'
		}
	);

</script>
</div> <!-- //inner -->

<div id='footer'>
Footer
</div> <!-- //footer -->

</div> <!-- //outer -->

</body>
</html>
