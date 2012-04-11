<?php
error_reporting(E_ALL);

/* pChart library inclusions */
require_once("charting.php");

require_once("DatabaseOperation/details.php");

/* Create and populate the pData object */
$data = new pData();

$db = db_connect();
$res = $db->query("SELECT DISTINCT DATE_FORMAT(AcceptedDate, '%d/%m/%Y'), Count(IdeaID) AS Ideas FROM Idea WHERE AcceptedDate IS NOT NULL GROUP BY DATE_FORMAT(AcceptedDate, '%d/%m/%Y') ORDER BY AcceptedDate desc limit 30;") or die($db->error);
While($row=$res->fetch_row()){
$data->addPoints($row[0],"Date");
$data->addPoints($row[1], "Ideas");
}

$db->close();

// turn lists around
$data->reverseSerie("Date");
$data->reverseSerie("Ideas");

$data->setAxisName($row[1],"Ideas");
$data->setAbscissa("Date"); // X-akselin otsikot

/* Create the pChart object */
$myPicture = new pImage(800,230,$data);

/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,800,230,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,800,230,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,800,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

/* Write the chart title */
$myPicture->drawText(230,55,"Ideoita per päivä",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

/* Draw the scale and the 1st chart */
$myPicture->setGraphArea(60,60,750,190);
$myPicture->drawFilledRectangle(60,60,750,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
$myPicture->drawScale(array("DrawSubTicks"=>TRUE));
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
$myPicture->drawBarChart(array("DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO,"Rounded"=>TRUE,"Surrounding"=>30));
$myPicture->setShadow(FALSE);

/* Render the picture (choose the best way) */
$myPicture->stroke();
?>