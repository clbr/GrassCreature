<?php
	error_reporting(E_ALL);
	require_once("details.php");

	function addIdea($name, $desc, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID) {
		// Add entirely new idea.
		$mysqli = db_connect();
		
		$sql = "INSERT INTO Idea (Name, Description, Version, RequestDate, Cost, AdditionalInfo, BasedOn, Inventor, Status, AddingDate) VALUES (
			?, ?, ?, ?, ?, ?, ?, ?, 'New', CURDATE())";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('ssisisii', $name, $desc, $version = 1, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID);
		$stmt->execute();
	}

	function saveVersion($ideaID, $version, $name, $desc, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID) {
		// When updating idea, saves the old version of it.
		$mysqli = db_connect();

		$sql = "INSERT INTO Version (IdeaID, Name, Status, Description, Version, RequestDate, Cost, AdditionalInfo, BasedOn, Inventor) VALUES (
			?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('isssiiissis', $ideaID, $name, 'oldVersion' , $desc, $version, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID);
		$stmt->execute();
	}

	function editIdea($ideaID, $name, $desc, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID) {
		$mysqli = db_connect();

		// Before editing all current info will be retrieved from db to textfields for user to edit.

		// Version incrementing has to be done by code, auto increment is no good here.
		$sql = "SELECT Version FROM Idea WHERE IdeaID = $ideaID";
		$version = $mysqli->query($sql) or die($mysqli->error);
		
		// Save previous version.
		saveVersion($ideaID, $version, $name, $desc, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID);	
		
		$version++;
		
		$sql = "UPDATE Idea SET Name = ?, SET Description = ?, SET Version = ?, SET RequestDate = ?, SET Cost = ?, SET AdditionalInfo = ?,
			SET BasedOn = ?, SET Inventor = ? WHERE IdeaID = $ideaID";
		
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('ssiisissi', $name, $desc, $version, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID);
		$stmt->execute();
	}

	function adminEditIdea($ideaID, $status, $name, $desc, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID) {
		$mysqli = db_connect();

		// Difference to normal editing: Can change status.

		// Before editing all current info will be retrieved from db to textfields for user to edit.

		// Version incrementing has to be done by code, auto increment is no good here.
		$sql = "SELECT Version FROM Idea WHERE IdeaID = $ideaID";
		$version = $mysqli->query($sql) or die($mysqli->error);
		
		// Save previous version.
		saveVersion($ideaID, $version, $name, $desc, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID);	
		
		$version++;
			
		$sql = "UPDATE Idea SET Name = ?, SET Description = ?, SET Version = ?, SET Status = ?, SET RequestDate = ?, SET Cost = ?, SET AdditionalInfo = ?,
			SET BasedOn = ?, SET Inventor = ? WHERE IdeaID = $ideaID";	
		
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('ssisiissi', $name, $desc, $version, $status, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID);
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


function getIdea($id) {

	$db = db_connect();

	$st = $db->prepare("select * from Idea where IdeaID=?");
	$st->bind_param('i', $id);

	$st->execute();

	$st->bind_result($ID, $Name, $Description, $Version, $Status, $Cost, $AddInfo, $BasedOn, $ReqDate, $AddDate, $Inventor);

	if ($st->fetch()) {
		echo "<div id=ideadiv>\n" .
			"<input type=hidden name=id value=$ID>\n" .
			"<table border=1>\n" .

			"\t<tr><td>Name</td><td>$Name</td></tr>\n" .
			"\t<tr><td>Description</td><td>$Description</td></tr>\n" .
			"\t<tr><td>Status</td><td>$Status</td></tr>\n" .
			"\t<tr><td>Cost</td><td>$Cost</td></tr>\n" .
			"\t<tr><td>Additional info</td><td>$AddInfo</td></tr>\n" .
			"\t<tr><td>Based on</td><td>$BasedOn</td></tr>\n" .
			"\t<tr><td>Requested date</td><td>$ReqDate</td></tr>\n" .
			"\t<tr><td>Added date</td><td>$AddDate</td></tr>\n" .
			"\t<tr><td>Inventor</td><td>$Inventor</td></tr>\n" .

//			"\t<tr><td></td><td></td></tr>\n" .

			"</table>\n" .
			"</div>\n";
	}

	$db->close();
}

?>
