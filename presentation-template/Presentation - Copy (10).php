<!DOCTYPE html>
	<html class="base present loaded">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Experiment: Slides</title>
		<meta name="description" content="Slides">

		<link rel="stylesheet" type="text/css" href="../lib/offline.css">
		<link rel="stylesheet" type="text/css" href="../lib/reveal.css">
		<link rel="stylesheet" type="text/css" href="../lib/pdf.css" media="print">

	</head>
	<body class="reveal-viewport theme-font-quicksand theme-color-grey-blue">
		<div class="reveal">
			
			<!-- Exported data begins here -->
			
				<div class="slides">
					<section><h2>Presentation Template</h2></section>
				</div>
			
			<!-- Exported data ends here -->
			
		</div>

		<script>
			var SLConfig = {
				current_user: {
					name: "William Widjaja",
					username: "williamwidjaja",
					pro: true
				},
				deck: {
					user: {
						name: "William Widjaja",
						username: "williamwidjaja"
					},
					id: "56036",
					slug: "experiment",
					title: "Experiment",
					description: "",
					access_token: "xxmJwqKLGpMqi8q8pUAqx332em4r",
					published: true,
					transition: "default",
					theme_font: "quicksand",
					theme_color: "grey-blue",
					rolling_links: true,
					should_loop: false,
					center: false,
					rtl: false
				}
			}
		</script>
		
		<script type='text/javascript' src="../js/jquery-1.9.1.js"></script>
		<script type='text/javascript' src="../lib/head.min.js"></script>
		<script type='text/javascript' src="../lib/reveal.min.js"></script>
		<script>
		
			<?php
				echo "var presentationId = '" . str_replace('.', '_', basename(__FILE__) ) . "';";
			?>
		
			Reveal.initialize({
				controls: true,
				progress: true,
				history: true,
				mouseWheel: false,
				
				width: 1120,
				height: 700,
				minScale: 0.5,
				maxScale: 1.2,
				margin: 0.05,
	
				rolling_links: SLConfig.deck.rolling_links || true,
				center: SLConfig.deck.center || false,
				loop: SLConfig.deck.should_loop || false,
				rtl: SLConfig.deck.rtl || false,

				transition: SLConfig.deck.transition,

				dependencies: [
					{ src: '../lib/reveal-plugins/markdown/marked.js', condition: function() { return !!document.querySelector( '[data-markdown]' ); } },
					{ src: '../lib/reveal-plugins/markdown/markdown.js', condition: function() { return !!document.querySelector( '[data-markdown]' ); } },
					{ src: '../lib/reveal-plugins/highlight/highlight.js', async: true, callback: function() { hljs.initHighlightingOnLoad(); } },
					{ src: '../lib/reveal-plugins/notes/notes.js', async: true, condition: function() { return !!document.body.classList; } }
				]
			});

			/*
			*	Code for master-client setup for presentation
			*/
			var timestamp = null; 
			var step = -1;
			function listener() {
				// Fetch latest info from server
				$.ajax({
					type: 'GET',
					url: 'subscribe-presentation.php',
					data: { 'timestamp' : timestamp, 'presentationId ' : presentationId },
					dataType: 'JSON',
					async: true,
					cache: false,
					success: function(data) {

						var position = eval(data['position'].replace(/[\\]/g,''));
						var nextStep = parseInt(data['step']);
						
						//check if the next step is higher than the current step. Move the slide only then.
						// if not, no need to do anything as the position data has reached late
						if (nextStep < step)
							return;
						
						step = nextStep;
						Reveal.slide(position[0].indexH, position[1].indexV, null);
						timestamp = data['timestamp'];
						
						// get next set of data
						listener();
					}
				});
			}

			// function to publish the position
			function publishPosition()
			{
				Reveal.addEventListener( 'slidechanged', function(event) {
					var position = '[{"indexH":"' + event.indexh + '"}, {"indexV":"' + event.indexv + '"}]';
					var step = (new Date()).getTime();
					
					// Send Position
					$.ajax({
						url: 'publish-presentation.php',
						data: { 'position' : position, 'presentationId' :  presentationId, 'isComplete' : 0, 'step' :  step}
					});
				});
			}

			$(document).ready(function(){

				//start the master when ctrl+m is pressed
				$(document).keydown(function(event) {
					if (!event.ctrlKey)
						return;
					
					if (event.keyCode == 77)
						alert("Activating Master");
					else
						return;
						
					publishPosition();
				});

				//join the presentation as client when ctrl+y is pressed
				$(document).keydown(function(event) {
					if (!event.ctrlKey)
						return;
					
					if (event.keyCode == 89)
						alert("Joining presentation as client");
					else
						return;

					// start listening to the presentation
					listener();
				});
				
				// activate the 'join as client' if the load url has join keyword in it
				var loadURL= window.location.href;
				if (loadURL.indexOf('join') != -1)
					listener();
				else if (loadURL.indexOf('master') != -1)
					publishPosition();
			});
		</script>
	</body>
</html>
