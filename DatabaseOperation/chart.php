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

function drawbg($pic) {

	/* Draw the background */
	$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
	$pic->drawFilledRectangle(0,0,800,230,$Settings);

	/* Overlay with a gradient */
	$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
	$pic->drawGradientArea(0,0,800,230,DIRECTION_VERTICAL,$Settings);
	$pic->drawGradientArea(0,0,800,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

}

// Each data gathering function returns a pImage object
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

	/* Create the pChart object */
	$pic = new pImage(800,230,$data);

	$data->setAxisName(0,"Kommentteja");
	$data->setAbscissa("Labels"); // X-akselin otsikot

	drawbg($pic);

	/* Write the chart title */
	$pic->drawText(250,55,"Kommentteja",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

	return $pic;
}

?>
