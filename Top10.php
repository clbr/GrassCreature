<?php
error_reporting(E_ALL);

// top 10 newest ideas
function top10_newest()
{
//Connection to db and info request
	require_once("DatabaseOperation/details.php");
	$mysqli = db_connect();
	$sql = "SELECT Name, IdeaID, date_format(AddingDate, '%a %D, %M %Y') FROM Idea ORDER BY AddingDate DESC limit 10";
	$result = $mysqli->query($sql) or die($mysqli->error);
	if($result)
	{
	//table creation and data insertion
		echo"<table border=0 width='100%'>";
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


function top10_commented()
{
//Connection to db and info request
	require_once("DatabaseOperation/details.php");
	$mysqli=db_connect();
	$sql = "SELECT Name, IdeaID, Count(Comment.CommentID) AS comments FROM Idea, " .
		"Comment WHERE Comment.Idea_IdeaID=Idea.IdeaID group by IdeaID " .
		"ORDER BY comments desc limit 10";
	$result = $mysqli->query($sql) or die($mysqli->error);
	if($result)
	{
	//table creation and data insertion
		echo"<table border=0 width='100%'>";
		$result->data_seek(0);
		$i=0;
		while($i<10)
		{
			$row = $result->fetch_row();
			if (!$row) break;

			echo"<tr><td><a href='showIdea.php?id=$row[1]'>$row[0]</a></td>
			<td>$row[2] comments</td>
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


// top 10 newest ideas
function top10_rated()
{
//Connection to db and info request
	require_once("DatabaseOperation/details.php");
	$mysqli = db_connect();

	$sql = "SELECT sum(Rating), Idea_IdeaID, Name FROM Rating inner join Idea on IdeaID = Idea_IdeaID group by Idea_IdeaID ORDER BY sum(Rating) DESC limit 10";
	$result = $mysqli->query($sql) or die($mysqli->error);
	if($result)
	{
	//table creation and data insertion
		echo"<table border=0 width='100%'>";
		$result->data_seek(0);
		$i=0;

		while($i<10)
		{
			$row = $result->fetch_row();
			if (!$row) break;

			echo "<tr><td><a href='showIdea.php?id=$row[1]'>$row[2]</a></td>
			<td>$row[0]</td>
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


// top 10 latest comments
function top10_latest_comments()
{
//Connection to db and info request
	require_once("DatabaseOperation/details.php");
	$mysqli = db_connect();
	$sql = "SELECT Name, IdeaID, date_format(Date, '%a %D %H:%i, %M %Y') FROM Idea, Comment WHERE Comment.Idea_IdeaID=Idea.IdeaID group by IdeaID " .
		"ORDER BY date desc limit 10";
	$result = $mysqli->query($sql) or die($mysqli->error);
	if($result)
	{
	//table creation and data insertion
		echo"<table border=0 width='100%'>";
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
