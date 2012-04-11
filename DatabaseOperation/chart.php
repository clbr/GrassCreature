<?php

error_reporting(E_ALL);

require_once("details.php");
require_once("charting.php");

// Get the limit in seconds between steps
function getTimeDelim($days) {

	$steps = 15;

	$then = ($days * 24 * 60 * 60) / $steps;

	return $then;
}

// Return UNIX time $days ago
function getStartTime($days) {

	$now = time();
	$then = $now - ($days * 24 * 60 * 60);

	return $then;
}

// Each data gathering function returns a pData object
function getCommentData($days) {

	$data = new pData();
	$start = getStartTime($days);
	$lim = getTimeDelim($days);

	$db = db_connect();

	$st = $db->prepare("select unix_timestamp(Date) as pvm from Comment where unix_timestamp(Date) >= ? order by pvm asc;") or die($db->error);

	$st->bind_param("i", $start);
	$st->execute() or die($db->error);
	$st->bind_result($sec);


	$com = 0;
	while ($st->fetch()) {
		$tick = $sec - $start;
		if ($tick < $lim) {
			$com++;
		} else {
			$data->addPoints($com, "Comments");
			$label = date("j M Y", $start);
			$data->addPoints($label, "Labels");

//			echo "$com comments at $label\n";

			$com = 0;
			$start += $lim;
		}
	}

	$db->close();

	return $data;
}

?>
