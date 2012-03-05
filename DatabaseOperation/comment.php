<?php
	error_reporting(E_ALL);
	
	/*require_once("details.php");
	$mysqli = db_connect(); ei mene näin funktiolle asti */
	
	function addComment ($text, $commentID, $ideaID) {	
		require_once("details.php");
		$mysqli = db_connect();		
		
		if ($mysqli)
			echo "yhteys";
		
		$sql = "INSERT INTO Comment (CommentID, Text, User_UserID, Idea_IdeaID, Date) VALUES (?, ?, ?, ?, ?)";
		$stmt = $mysqli->prepare($sql);				
		$stmt->bind_param('isiis', $commentID, $text, $userID, $ideaID, $date);
		
		$userID = 1;
		$date = date('Y-m-d');
		
		$stmt->execute();
		echo "loppu.";
	}
	
	/*if ($mysqli)
			echo "yhteys";*/
			
		/*$sql = "SELECT * FROM TASK";
		$result = $mysqli->query($sql) or die($mysqli->error);
		
		echo "ID date groupID hours task dudeID<br>";
		while ($obj = $result->fetch_object()) {
			echo $obj->TaskID. " " . $obj->Date . " " . $obj->GroupID . " " . $obj->Hours . " " . $obj->Task . " " . $obj->DudeID . "<br>";
		}*/
?>