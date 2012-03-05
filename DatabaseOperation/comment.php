<?php
	error_reporting(E_ALL);
	
	require_once("details.php");
	
	function addComment ($text, $commentorID, $ideaID) {
		$mysqli = db_connect();		
		
		if ($mysqli)
			echo "yhteys";
		
		$sql = "INSERT INTO Comment (Text, User_UserID, Idea_IdeaID, Date) VALUES (?, ?, ?, ?)";
		$stmt = $mysqli->prepare($sql);				
		$stmt->bind_param('siis', $text, $commentorID, $ideaID, date('Y-m-d'));
				
		//$date = date('Y-m-d'); // can be inserted straight to bind_param.
		
		$stmt->execute();
		echo "loppu.";
	}
	
	function getComments() { 
		$mysqli = db_connect();	
		
		$ideaID = 5; // normally id as paramater to this function.
		
		// If there are column name collisions, use User.Name etc. instead.
		$sql = "SELECT Date, Name, Company, Text FROM Comment LEFT OUTER JOIN User ON Comment.User_UserID = User.UserID WHERE Idea_IdeaID = $ideaID";
		$result = $mysqli->query($sql) or die($mysqli->error);		
		
		while ($obj = $result->fetch_object()) {
			echo $obj->Date . " " . $obj->Name . " " . $obj->Company . " " . $obj->Text . "<br>";
		}
	}
?>