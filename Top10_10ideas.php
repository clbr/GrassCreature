

<?
error_reporting(E_ALL);
function top102()
{
//Connection to db and info request
	require_once("details.php");
	$mysqli = functiondb_connect();
	if(mysqli_connect_errno())
	{
	echo "Yhteyttä tietokantaan ei saatu: ";
	echo mysqli_connect_error();
	}
	$sql = "SELECT Name, Inventor, AddingDate FROM Idea ORDER BY AddingDate DESC";
	$result = $mysqli->query($sql) or die($mysqli->error);
	if($result)
	{
	//table creation and data insertion
		echo"<table border='1'>";
		$result->data_seek(0);
		echo "<tr><td>Idea Name</td><td>Inventor</td><td>Time when added</td></tr>";
		
		for($i=0; $i<10; $i++)
		{
		$row = $result->fetch_row();
			echo"<tr>
			<td>$row[0]</td>
			<td>$row[1]</td>
			<td>$row[2]</td>
			
			</tr>";
			
			//echo "<pre>"; var_dump($row); echo "</pre>";
		}
		echo"</table>";
		$result->close();
	}
	else{echo "Error";}
	//disconnect
	$mysqli->close();
}
?>


