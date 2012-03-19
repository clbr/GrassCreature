<?php
error_reporting(E_ALL);
//function top102()
//{
//Connection to db and info request
	require_once("details.php");
	$mysqli=db_connect();
	$sql = "SELECT Name, Inventor, Count(Comment.Idea_IdeaID=Idea.IdeaID) AS comments FROM Idea, Comment WHERE Comment.Idea_IdeaID=Idea.IdeaID";
	$result = $mysqli->query($sql) or die($mysqli->error);
	if($result)
	{
	//table creation and data insertion
		echo"<table border='1'>";
		$result->data_seek(0);
		echo"<tr><td>Name</td><td>Link</td><td>Accepted date</td></tr>\n";
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
//}
?>