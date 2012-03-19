<?php
error_reporting(E_ALL);
function top102()
{
//Connection to db and info request
	require_once("DatabaseOperation/details.php");
	$mysqli=db_connect();
	$sql = "SELECT Name, Inventor, AddingDate FROM Idea ORDER BY AddingDate DESC limit 10";
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
			echo"<tr><td>$row[0]</td>
			<td>$row[1]</td>
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
