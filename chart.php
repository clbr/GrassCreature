<?php
error_reporting(E_ALL);

/* pChart library inclusions */
require_once("charting.php");

require_once("DatabaseOperation/details.php");

if (!isset($_GET["what"]) || !isset($_GET["period"]))
	return;


switch($_GET["what"]) {
	case "comments":
		$pic = $getCommentData($_GET["period"]);
	break;

	default:
		header("Location: img/notdone.png");
		return;
	break;
}

/* Draw the scale and the 1st chart */
$pic->setGraphArea(60,60,750,190);
$pic->drawFilledRectangle(60,60,750,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
$pic->drawScale(array("DrawSubTicks"=>TRUE));
$pic->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
$pic->drawBarChart(array("DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO,"Rounded"=>TRUE,"Surrounding"=>30));
$pic->setShadow(FALSE);

/* Render the picture */
$pic->stroke();
?>
