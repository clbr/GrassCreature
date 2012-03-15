<?php
	error_reporting(E_ALL);
	require_once("details.php");

	function addIdea($name, $desc, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID) {
		// Add entirely new idea.
		$mysqli = db_connect();

		$sql = "INSERT INTO Idea (Name, Description, Version, RequestDate, Cost, AdditionalInfo, BasedOn, Inventor, Status, AddingDate) VALUES (
			?, ?, ?, ?, ?, ?, ?, ?, 'New', NOW())";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('ssisisii', $name, $desc, $version = 1, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID);
		$stmt->execute();

		// Return the id of the just created idea. Will be used for uploaded image location.
		$sql = "SELECT LAST_INSERT_ID()";
		if ($result = $mysqli->query($sql) or die($mysqli->error))
			$just_added_id = $result->fetch_row();

		return $just_added_id[0];
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

	function editIdea($ideaID, $name, $desc, $reqdate, $cost, $additionalInfo, $basedOn, $version, $inventorID) {
		$mysqli = db_connect();

		// Before editing all current info will be retrieved from db to textfields for user to edit.

		// Version incrementing has to be done by code, auto increment is no good here.
		//$sql = "SELECT Version FROM Idea WHERE IdeaID = $ideaID";
		//$version = $mysqli->query($sql) or die($mysqli->error);
		
		// ^ currently obsolete, since found a better way to get version...
		$version++;

		$sql = "UPDATE Idea SET Name = ?, SET Description = ?, SET Version = ?, SET RequestDate = ?, SET Cost = ?, SET AdditionalInfo = ?,
			SET BasedOn = ?, SET Inventor = ? WHERE IdeaID = $ideaID";

		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('ssiisissi', $name, $desc, $version, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID);
		$stmt->execute();
		
		// Return ID for images.
		return $ideaID;
	}

	function adminEditIdea($ideaID, $status, $name, $desc, $reqdate, $cost, $additionalInfo, $basedOn,$version, $inventorID) {
		$mysqli = db_connect();

		// Difference to normal editing: Can change status.

		// Before editing all current info will be retrieved from db to textfields for user to edit.

		// Version incrementing has to be done by code, auto increment is no good here.
		//$sql = "SELECT Version FROM Idea WHERE IdeaID = $ideaID";
		//$version = $mysqli->query($sql) or die($mysqli->error);
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

	function getIdeaInfo($id) {
		$mysqli = db_connect();
		
		$sql = "select * from Idea where IdeaID=$id";
		$result = $mysqli->query($sql) or die($mysqli->error);
		return $result;
	}

function getIdea($id, $userID) {

	$db = db_connect();

	$st = $db->prepare("select * from Idea where IdeaID=?");
	$st->bind_param('i', $id);

	$st->execute();

	$st->bind_result($ID, $Name, $Description, $Version, $Status, $Cost, $AddInfo, $BasedOn, $ReqDate, $AddDate, $Inventor);

	if ($st->fetch()) {
		$st->close();

		$st = $db->prepare("select Name from User where UserID = ?");
		$st->bind_param('i', $Inventor);
		$st->execute();
		$st->bind_result($uname);
		$st->fetch();

		echo "<div id=ideadiv>\n" .
			"<input type=hidden name=id value=$ID>\n" .
			"<table border=0>\n" .

			"\t<tr><td>Name</td><td>$Name</td></tr>\n" .
			"\t<tr><td>Description</td><td>$Description</td></tr>\n" .
			"\t<tr><td id=idealeft>Status</td><td>$Status</td></tr>\n";

		if (!empty($Cost)) echo "\t<tr><td>Cost</td><td>$Cost</td></tr>\n";
		if (!empty($AddInfo)) echo "\t<tr><td>Additional info</td><td>$AddInfo</td></tr>\n";
		if (!empty($BasedOn)) echo "\t<tr><td>Based on</td><td><a href=\"showIdea.php?id=$BasedOn\">$BasedOn</a></td></tr>\n";

		echo "\t<tr><td>Requested date</td><td>$ReqDate</td></tr>\n" .
			"\t<tr><td>Added date</td><td>$AddDate</td></tr>\n" .
			"\t<tr><td>Inventor</td><td><a href=\"showUser.php?id=$Inventor\">$uname</a></td></tr>\n" .

//			"\t<tr><td></td><td></td></tr>\n" .

			"</table>\n" .
			"</div>\n";
		
		// Edit button for created ideas.
		if ($userID == $Inventor) {
			// Send idea-id along page change.
			echo "<a href='editIdea.php?ideaid=$id'>Edit idea</a>";	
		}		
		
	}
	$db->close();
}

?>
