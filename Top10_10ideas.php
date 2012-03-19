<?php
error_reporting(E_ALL);

// top 10 newest ideas
function top102()
{
//Connection to db and info request
	require_once("DatabaseOperation/details.php");
	$mysqli = db_connect();
	$sql = "SELECT Name, IdeaID, date_format(AddingDate, '%a %D, %M %Y') FROM Idea ORDER BY AddingDate DESC limit 10";
	$result = $mysqli->query($sql) or die($mysqli->error);
	if($result)
	{
	//table creation and data insertion
		echo"<table border='1'>";
		$result->data_seek(0);
		$i=0;
		while($i<10)
		{
		$row = $result->fetch_row();
			echo"<tr><td><a href='showIdea.php?id=$row[1]'>$row[0]</a></td>
			<td>$row[2]</td>
			</tr>\n";
			$i++;
		}
		echo "</table>";
		$result->close();
	}
	else{echo "Error";}
	//disconnect
	$mysqli->close();
}
?>
