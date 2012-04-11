<?php

error_reporting(E_ALL);

require_once("details.php");
require_once("../charting.php");

// Get the limit in seconds between steps
function getTimeDelim($days) {

	$steps = 15;

	$then = ($days * 24 * 60 * 60) / $steps;

	return $then
}

// Return UNIX time $days ago
function getStartTime($days) {

	$now = time();
	$then = $now - ($days * 24 * 60 * 60);

	return $then;
}

?>
