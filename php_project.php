<?php
/**
 * Created by PhpStorm.
 * User: Tyson Glore
 */
include_once "includes/get_vettix_events.php";

// Get events from endpoint and decode JSON
$eventAPIResults = get_events();
$events = json_decode($eventAPIResults);

// Set current time to compare to event times, create an array to hold events that meet criteria.
$outputEvents = array();

foreach($events->events as $event) {
	// Make sure at least one offer is enabled.
	$offerEnabled = false;
	foreach($event->offers as $eventOffers) {
		if($eventOffers->enabled == true) {
			$offerEnabled = true;
		}
	}
	// If an offer is enabled then compare event date and time to now.
	if($offerEnabled == true) {
		$eventDateTime = new DateTime($event->eventDate.' '.$event->eventTime, new DateTimeZone($event->timeZone));
		$currentDateTime = new DateTime('now', new DateTimeZone($event->timeZone));
		if($eventDateTime > $currentDateTime) {
			// Offer is enabled and event is in the future, add event to array of objects for display in HTML.
			$newEvent = new stdClass();
			$newEvent->eventId = $event->eventId;
			$newEvent->venuId = $event->venueId;
			$newEvent->eventLocalDateTime = $eventDateTime;
			$vtfDateTime = clone $eventDateTime;
			$vtfDateTime->setTimezone(new DateTimeZone('America/New_York'));
			$newEvent->eventVTDateTime = $vtfDateTime;
			$outputEvents[] = $newEvent;
		}
	}
}


?>
<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Vet Tix Events</title>
	<meta name="description" content="PHP Project">
	<meta name="author" content="Tyson Glore">
	<link rel="stylesheet" href="css/styles.css">
</head>

<body>
	<section>
		<button type="button" onclick="document.location='index.php'">Back</button>
		<h3>Events using PHP</h3>
		<?php if(count($outputEvents)):?>
			<?php foreach($outputEvents as $thisEvent):?>
				<div class="event_container">
					<p>Event ID: <?php echo $thisEvent->eventId;?></p>
					<p>Venu ID: <?php echo $thisEvent->venuId;?></p>
					<p>Event Date Time: <?php echo date_format($thisEvent->eventLocalDateTime, 'm/d/Y g:i A');?></p>
					<p>Event VTF Date Time: <?php echo date_format($thisEvent->eventVTDateTime, 'm/d/Y g:i A');?></p>
				</div>
			<?php endforeach;?>
		<?php endif;?>
	</section>
</body>
</html>