<?php
/**
 * Created by PhpStorm.
 * User: Tyson Glore
 */
?>

<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Vet Tix Events</title>
	<meta name="description" content="JS Project">
	<meta name="author" content="Tyson Glore">
	<link rel="stylesheet" href="css/styles.css">
	<script type="application/javascript" src="js/moment.min.js"></script>
	<script type="application/javascript" src="js/moment-timezone-with-data-10-year-range.min.js"></script>
</head>

<body>
	<section>
		<button type="button" onclick="document.location='index.php'">Back</button>
		<h3>Events using JavaScript</h3>
		<div id="content"></div>
	</section>
</body>
<script type="application/javascript">
	// Send request for data from the API endpoint
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("GET", "https://www.vettix.org/sandbox/api/tm-events.php", false);
	xmlHttp.setRequestHeader("HTTP_CUSTOM_VETTIX", "aoekjr02%weragwkL51");
	xmlHttp.send(null);

	// Get the returned events and parse the JSON
	var rawVetTixEvents = xmlHttp.responseText;;
	var vetTixEvents = JSON.parse(rawVetTixEvents);

	// Set the var to append HTML of the events
	var contentDiv = document.querySelector('div#content');
	//moment.tz.setDefault("America/New_York");
	// Loop through events
	vetTixEvents.events.forEach(function(event) {
		// Loop through the offers to see if at least one is enabled
		var offerEnabled = false;
		event.offers.forEach(function(offer) {
			if(offer.enabled) {
				offerEnabled = true;
			}
		});
		// If an offer is enabled then we will create the dates to compare if not we move to the next
		if(offerEnabled) {
			// Create current datetime object in the event's timezone and the event datetime object to compare
			var currentDateTime = moment.tz(event.timeZone);
			var eventDateTime = moment.tz(event.eventDate+' '+event.eventTime, event.timeZone);
			var eventVtfDateTime = eventDateTime.clone().tz('America/New_York');
			if(eventDateTime > currentDateTime) {
				// All conditions met so append the HTML to
				var html='<div class="event_container">';
				html += '<p>Event ID: '+event.eventId+'</p>';
				html += '<p>Venu ID: '+event.venueId+'</p>';
				html += '<p>Event Date Time: '+eventDateTime.format("MM/DD/YYYY h:mm A")+'</p>';
				html += '<p>Event VTF Date Time: '+eventVtfDateTime.format("MM/DD/YYYY h:mm A")+'</p>';
				html += '</div>';
				contentDiv.insertAdjacentHTML('beforeend', html);
			}
		}
	});
</script>
</html>