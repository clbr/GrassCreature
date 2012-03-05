<?php

require_once("details.php");

function addIdea($name, $desc) {

	$mysqli = db_connect();

	$sql = "INSERT INTO Idea (Name, Description, Version, Status, RequestDate, Inventor) VALUES ('$name', '$desc', 0, 'new', CURDATE(), 1)";
	$result = $mysqli->query($sql);

	if(!$result) {
		$mysqli->close();
		return 1;
	}

	$mysqli->close();
	return 0;

}

function removeIdeaPermanently($ideaID) {
	require("details.php");
	$mysqli = db_connect();	
	
	$sql = "DELETE FROM Idea WHERE IdeaID = $ideaID";
	$mysqli->query($sql) or die($mysqli->error);	
	$mysqli->close();
}

function addVote($rating, $ideaID, $userID) {
	require("details.php");
	$mysqli = db_connect();	
	
	$sql = "INSERT INTO Rating (Rating, Idea_IdeaID, User_UserID) VALUES ($rating, $ideaID, $userID)";
	$mysqli->query($sql) or die($mysqli->error);	
	$mysqli->close();
}


?>
