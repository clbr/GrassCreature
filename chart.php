<?php
//function chart
//{
	//standard includes
	include("pchart/class/pData.class.php");  
	include("pchart/class/pDraw.class.php");
	include("pchart/class/pImage.class.php");
	
	// creating dataobject
	$OneData=new pData();
	
	//db actions
	require_once("details.php");
	$mysqli = db_connect();
	$sql = "SELECT Distinct DATE_FORMAT(Date, '%d/%m/%Y' ) As vrk, count(CommentID) AS Comments FROM Comment GROUP BY vrk ORDER BY Date desc limit 30";
	$result = $mysqli->query($sql) or die($mysqli->error);
	$Date=""; $Count="";
	while($row=mysql_fetch_array($result))
	{
	//get data from query result
	$Date[]=$row["vrk"];
	$Count[]=$row["Comments"];
	}
	//save data in pData array
	$OneData->addPoints($Date,"Date");
	$OneData->addPoints($Count, "Comments");
	
	//abscissa axis
	$OneData->setAbscissa("Date");
	
	//associate "Comments" Dataserie to the second axis
	$Onedata->setSerieOnAxis("Comments", 0);
	
	//Name this axis "Time"
	$OneData->setXAxisName("Date");
	
	//Y-axis
	$Onedata->setAxisName(0, "Comments");
	$OneData->setAxisUnit(0, "Comments");
	
	//Drawing:
	
	//pchart object & data association
	$OneChart = new pImage(700,230,$OneData);
	
	//Antialiasing off
	$OneChart->Antialias = FALSE;
	
	//border for picture
	$OneCHart->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));
	$OneChart->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>20));
	$OneChart->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));
	
	//font
	$OneChart->setFontProperties(array("FontName"=>"pchart/fonts/GeosansLight.ttf","FontSize"=>12));
	
	//Draw scale
	$scaleSettings = array("GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
	$OneChart->drawScale($scaleSettings);
	
	//chart legend
	$OneChart->drawLegend(580,12,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));
	
	//shadow on
	$OneChart->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
	
	//draw chart
	$OneChart->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
	$settings = array("Surrounding"=>-30,"InnerSurrounding"=>30);
	$OneChart->drawBarChart($settings);
	
	//render
	$OnceChart->autoOutput("example.drawBarChart.simple.png");
?>