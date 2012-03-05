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

function editIdea($ideaID, $name, $desc, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID, $addDate) {
	$mysqli = db_connect();
	
	// Before editing all current info will be retrieved from db to textfields for user to edit.
	
	// Version incrementing has to be done by code, auto increment is no good here.
	// Gets the biggest version number from this id.
	$sql = "SELECT Version FROM Idea WHERE IdeaID = $ideaID ORDER BY Version DESC LIMIT 1";
	$version = $mysqli->query($sql) or die($mysqli->error);
	$version++;
	
	$sql = "INSERT INTO Idea (Name, Description, Version, RequestDate, Cost, AdditionalInfo, BasedOn, Inventor, AddingDate) VALUES (
		?, ?, ?, ?, ?, ?, ?, ?, CURDATE())"; // CURDATE() goes to adding date, should there be a "edited on" column?
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param('ssississi', $name, $desc, $version, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID);	
	$stmt->execute();
}

function adminEditIdea($ideaID, $status, $name, $desc, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID, $addDate) {
	$mysqli = db_connect();
	
	// Difference to normal editing: Can change status.
	
	// Before editing all current info will be retrieved from db to textfields for user to edit.
	
	// Version incrementing has to be done by code, auto increment is no good here.
	// Gets the biggest version number from this id.
	$sql = "SELECT Version FROM Idea WHERE IdeaID = $ideaID ORDER BY Version DESC LIMIT 1";
	$version = $mysqli->query($sql) or die($mysqli->error);
	$version++;
	
	$sql = "INSERT INTO Idea (Name, Description, Version, Status, RequestDate, Cost, AdditionalInfo, BasedOn, Inventor, AddingDate) VALUES (
		?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE())";	
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param('ssississi', $name, $desc, $version, $status, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID);	
	$stmt->execute();
}

function removeIdeaPermanently($ideaID) {
	$mysqli = db_connect();	
	
	$sql = "DELETE FROM Idea WHERE IdeaID = $ideaID";
	$mysqli->query($sql) or die($mysqli->error);	
	$mysqli->close();
}

function addVote($rating, $ideaID, $userID) {
	$mysqli = db_connect();	
	
	$sql = "INSERT INTO Rating (Rating, Idea_IdeaID, User_UserID) VALUES ($rating, $ideaID, $userID)";
	$mysqli->query($sql) or die($mysqli->error);	
	$mysqli->close();
}


?>
