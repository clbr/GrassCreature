<?php
error_reporting(E_ALL);

require_once("DatabaseOperation/chart.php");

if (!isset($_GET["what"]) || !isset($_GET["period"]))
	return;

$period = $_GET["period"];

switch($_GET["what"]) {
	case "comments":
		$pic = getCommentData($period);
	break;

	default:
		header("Location: img/notdone.png");
		return;
	break;
}

/* Draw the scale and the chart */
$pic->setGraphArea(60,60,830,190);
$pic->drawFilledRectangle(60,60,830,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
$pic->drawScale(array("DrawSubTicks"=>TRUE));
$pic->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
$pic->drawSplineChart(array("DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO,"Rounded"=>TRUE,"Surrounding"=>30));
$pic->setShadow(FALSE);

/* Render the picture */
$pic->stroke();
?>
