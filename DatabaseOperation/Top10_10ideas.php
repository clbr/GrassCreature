
<?php
function top101()
{
	require_once("S:\www\details.php"); 
	$yhteys = functiondb_connect(); 
	$sql = "SELECT * FROM Idea ORDER BY AddingDate DESC";
	$result = $mysqli->query($sql) or die($mysqli->error);
	if($result)
	{
		echo"<table border='1'>";
		$result->data_seek(0);
		while($row = $result->fetch_row())
		{
			echo"<tr><td>$row[0]</td>
			<td>$row[1]</td>
			<td>$row[2]</td>
			<td>$row[3]</td>
			<td>$row[4]</td>
			<td>$row[5]</td>
			<td>$row[6]</td>
			<td>$row[7]</td>
			<td>$row[8]</td>
			<td>$row[9]</td><td>",$row[0]+$row[1]+$row[2]+$row[3]+$row[4]+$row[5]+$row[6]+$row[7]+$row[8]+$row[9],"</td>
			</tr>\n";
		}
		echo "</table>";
		$result->close();
	}
	else{echo "Error";}
	$yhteys->close();
}
?>
