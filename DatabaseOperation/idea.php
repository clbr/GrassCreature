<?php

error_reporting(E_ALL);
require_once("details.php");
require_once('DatabaseOperation/perms.php');

	function addIdea($name, $desc, $reqdate, $cost, $additionalInfo, $basedOn, $perms, $inventorID, $categories) {
		// Add entirely new idea.
		$mysqli = db_connect();

		$name = htmlspecialchars($name);
		$desc = htmlspecialchars($desc);
		$additionalInfo = htmlspecialchars($additionalInfo);

		$sql = "INSERT INTO Idea (Name, Description, Version, RequestDate, Cost, AdditionalInfo, BasedOn, Inventor, Status, StatusLastEdited, AddingDate) VALUES (
			?, ?, ?, STR_TO_DATE(?, '%e.%c.%Y'), ?, ?, ?, ?, 'new', NOW(), NOW())";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('ssisisii', $name, $desc, $version = 1, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID);
		$stmt->execute();
		$stmt->close();

		// Return the id of the just created idea. Will be used for uploaded image location.
		$sql = "SELECT LAST_INSERT_ID()";
		if ($result = $mysqli->query($sql) or die($mysqli->error))
			$just_added_id = $result->fetch_row();

		if ($perms == 'restrict') {
			$st = $mysqli->prepare("insert into Idea_has_Group (Idea_IdeaID, Group_GroupID, CanView) values (?, 0, false)") or die($mysqli->error);
			$st->bind_param("i", $just_added_id[0]);
			$st->execute() or die($mysqli->error);
		} else { // Mark everyone as 'can view, comment', the defaults
			$st = $mysqli->prepare("insert into Idea_has_Group (Idea_IdeaID, Group_GroupID) values (?, 0)") or die($mysqli->error);
			$st->bind_param("i", $just_added_id[0]);
			$st->execute() or die($mysqli->error);
		}

		// Replace more than one space with one space
		$categories = str_replace("  ", " ", $categories);

		//add category
		$category = explode(" ", $categories);
		foreach ($category as $value) {
			addCategory($just_added_id[0], $value);
		}

		return $just_added_id[0];
	}

	function addCategory($ideaID, $category){

		$mysqli = db_connect();
                $sql = "SELECT CategoryID FROM `ideapankki_dev`.`Category` WHERE Name=?;";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('s', $category);
                $stmt->execute();
		$stmt->bind_result($categoryID);
		$stmt->fetch();
                $stmt->close();
		if($categoryID>0) {
		$sql = "insert into Idea_has_Category (Idea_IdeaID, Category_CategoryID) values (?,?);";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('ss', $ideaID, $categoryID);
                $stmt->execute();
                $stmt->close();	
		}
		else {
                $sql = "insert into Category (Name, Description) values (?, 'kuvaus');";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('s', $category);
                $stmt->execute();
                $stmt->close();
                $sql = "SELECT LAST_INSERT_ID();";
                $stmt = $mysqli->prepare($sql);
                $stmt->execute();
                $stmt->bind_result($categoryID);
                $stmt->fetch();
                $stmt->close();
		$sql = "insert into Idea_has_Category (Idea_IdeaID, Category_CategoryID) values (?,?);";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('ss', $ideaID, $categoryID);
                $stmt->execute();
                $stmt->close();
		}


	}

	function getCategory($ideaID) {

                $mysqli = db_connect();
                $sql = "SELECT Name FROM Category LEFT JOIN Idea_has_Category ON Category.CategoryID = Idea_has_Category.Category_CategoryID WHERE Idea_IdeaID=?;";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('s', $ideaID);
                $stmt->execute();


		$stmt->bind_result($name);
		$stmt->store_result();


		$category = array();

        	while($stmt->fetch()) {

                $category[] = $name;

		}

                $stmt->close();
		return $category;

	}

	function getMostUsedCategories() {
                $mysqli = db_connect();
                $sql = "SELECT Name FROM Category LEFT JOIN Idea_has_Category ON Category.CategoryID = Idea_has_Category.Category_CategoryID GROUP BY Category_CategoryID ORDER BY COUNT(Idea_IdeaID) DESC LIMIT 10;";
                $stmt = $mysqli->prepare($sql);
                $stmt->execute();


                $stmt->bind_result($name);
                $stmt->store_result();


                $category = array();

                while($stmt->fetch()) {

                $category[] = $name;

                }

                $stmt->close();
                return $category;

	}

	function saveVersion($ideaID, $version, $status, $name, $desc, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID) {
		// When updating idea, saves the old version of it.
		$mysqli = db_connect();

		$sql = "INSERT INTO Version (IdeaID, Name, Status, Description, Version, RequestDate, Cost, AdditionalInfo, BasedOn, Inventor) VALUES (
			?, ?, ?, ?, ?, STR_TO_DATE(?, '%e.%c.%Y'), ?, ?, ?, ?)";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('isssisisii', $ideaID, $name, $status , $desc, $version, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID);
		$stmt->execute();
	}
	
	function getVersions($ideaID) {
		try {
			$pdo = pdo_connect();

			$sql = "SELECT VersionID, IdeaID, Version.Name, Description, Version, Status, Cost, AdditionalInfo, BasedOn,
				DATE_FORMAT(RequestDate, '%e.%c.%Y') AS RequestDate, DATE_FORMAT(AddingDate, '%e.%c.%Y %H:%i:%s') AS AddingDate, Inventor, DATE_FORMAT(AcceptedDate, '%e.%c.%Y %H:%i:%s') AS AcceptedDate,
				User.Name AS Username
				FROM Version
				LEFT OUTER JOIN User
				ON Version.Inventor = User.UserID
				WHERE IdeaID = :IdeaID ORDER BY Version DESC";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':IdeaID', $ideaID);
			$stmt->execute();
			$pdo = null; // Close connection.

			$versions = array();
			while ($version = $stmt->fetch(PDO::FETCH_OBJ))
				$versions[] = $version;

			//echo "<pre>"; var_dump($stmt); echo "</pre>";
			return $versions;
		}
		catch (PDOException $err)
		{
			echo $err;
		}
	}

	function editIdea($ideaID, $name, $desc, $reqdate, $cost, $additionalInfo, $basedOn, $version, $inventorID) {
		$mysqli = db_connect();

		$name = htmlspecialchars($name);
		$desc = htmlspecialchars($desc);
		$additionalInfo = htmlspecialchars($additionalInfo);

		// Before editing all current info will be retrieved from db to textfields for user to edit.
		$version++;

		$sql = "UPDATE Idea SET Name = ?, Description = ?, Version = ?, RequestDate = STR_TO_DATE(?, '%e.%c.%Y'), Cost = ?, AdditionalInfo = ?,
			BasedOn = ? WHERE IdeaID = ?";

		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('ssisisii', $name, $desc, $version, $reqdate, $cost, $additionalInfo, $basedOn, $ideaID);
		$stmt->execute();

		// Return ID for images.
		return $ideaID;
	}


	// The inventor abandons his idea; it is marked abandoned
	function abandonIdea($ideaID) {

		$db = db_connect();

		$stmt = $mysqli->prepare("UPDATE Idea SET Status = 'abandoned' WHERE IdeaID = ?") or die($db->error);
		$stmt->bind_param('i', $ideaID);
		$stmt->execute();
		$db->close();
	}

	function adminEditIdea($ideaID, $status, $status_changed, $name, $desc, $reqdate, $cost, $additionalInfo, $basedOn,$version, $inventorID) {
		$mysqli = db_connect();

		$name = htmlspecialchars($name);
		$desc = htmlspecialchars($desc);
		$additionalInfo = htmlspecialchars($additionalInfo);

		// Difference to normal editing: Can change status.

		// Before editing all current info will be retrieved from db to textfields for user to edit.
		$version++;

		$sql = "UPDATE Idea SET Name = ?, Description = ?, Version = ?, Status = ?, RequestDate = STR_TO_DATE(?, '%e.%c.%Y'), Cost = ?, AdditionalInfo = ?,
				BasedOn = ?, Inventor = ?";

		if ($status_changed)
			 $sql .= ", StatusLastEdited = NOW()";

		$sql .= " WHERE IdeaID = ?";

		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('ssisiisiii', $name, $desc, $version, $status, $reqdate, $cost, $additionalInfo, $basedOn, $inventorID, $ideaID);
		$stmt->execute();
	}

function addVote($vote, $ideaID, $userID) {

	if($vote==-1||$vote==1) {
		$mysqli = db_connect();

		$sth = $mysqli->prepare("select COUNT(User_UserID) from Rating where Idea_IdeaID=? and User_UserID=?;") or die($mysqli-error);
		$sth->bind_param("ii", $ideaID, $userID);
		$sth->execute();
		$sth->bind_result($user);
		$sth->fetch();

		if($user==0) {

			$sth->close();

			$sth = $mysqli->prepare("INSERT INTO Rating (Rating, Idea_IdeaID, User_UserID) VALUES (?, ?, ?);") or die($mysqli->error);
			$sth->bind_param("sss", $vote, $ideaID, $userID);
			$sth->execute();
			$mysqli->close();
		} else {
			echo "<h3>You have already voted on this idea.</h3>\n";
		}
	}
}

function getVote($ideaID) {

	$mysqli = db_connect();

	$sth = $mysqli->prepare("select sum(Rating) from Rating where Idea_IdeaID=?;");
	$sth->bind_param("i", $ideaID);
	$sth->execute();

	$sth->bind_result($rating);

	$result=0;
	if ($sth->fetch()) {
		$result+=$rating;
	}

	return $result;

}

	function getIdeaInfo($ideaID) {
		$mysqli = db_connect();

		$sql = "select IdeaID, Name, Description, Version, Status, Cost, AdditionalInfo, BasedOn, DATE_FORMAT(RequestDate, '%e.%c.%Y') AS RequestDate, Inventor,
			DATE_FORMAT(AddingDate, '%e.%c.%Y %H:%i:%s') AS AddingDate,  DATE_FORMAT(StatusLastEdited, '%e.%c.%Y %H:%i:%s') AS StatusLastEdited
			from Idea where IdeaID=$ideaID";
		$result = $mysqli->query($sql) or die($mysqli->error);
		return $result;
	}

	function getMyIdeas($userID) {
		$mysqli = db_connect();
		// Could fetch amount of comments too and maybe rating.
		$sql = "select IdeaID, Name, Status, DATE_FORMAT(AddingDate, '%e.%c.%Y %H:%i:%s') AS AddingDate from Idea where Inventor=$userID ORDER BY AddingDate DESC";
		$result = $mysqli->query($sql) or die($mysqli->error);
		return $result;
	}

function getIdeaName($id) {

	$db = db_connect();

	$st = $db->prepare("select Name from Idea where IdeaID = ?") or die($db->error);
	$st->bind_param("i", $id);
	$st->execute();
	$st->bind_result($name);
	$st->fetch();

	$db->close();

	return $name;
}

function getIdeaInventor($id) {

	$db = db_connect();

	$st = $db->prepare("select Inventor from Idea where IdeaID = ?") or die($db->error);
	$st->bind_param("i", $id);
	$st->execute();
	$st->bind_result($name);
	$st->fetch();

	$db->close();

	return $name;
}

// The following layer violation is explained by crappy PHP - no fetch_array etc
// possible when using a parameterized query (!!)
function getIdea($id, $userID, $isAdmin) {

	global $sess;

	$categoryString="";
	$category=getCategory($id);
	foreach($category as $value) {
		$categoryString=$categoryString.$value." ";
	}

	$db = db_connect();

	// Admin can view any idea.
	if (!$isAdmin && !canView($id, $userID))
		die("You don't have permission to view this idea.");

	$st = $db->prepare("select IdeaID, Name, Description, Version, Status, DATE_FORMAT(StatusLastEdited, '%e.%c.%Y %H:%i:%s') AS StatusLastEdited, Cost,
		AdditionalInfo, BasedOn, DATE_FORMAT(RequestDate, '%e.%c.%Y') AS RequestDate, DATE_FORMAT(AddingDate, '%e.%c.%Y %H:%i:%s') AS AddingDate,
		Inventor, DATE_FORMAT(AcceptedDate, '%e.%c.%Y %H:%i:%s') AS AcceptedDate
		from Idea where IdeaID=?");
	$st->bind_param('i', $id);	

	$st->execute();

	$st->bind_result($ID, $Name, $Description, $Version, $Status, $StatusLastEdited, $Cost, $AddInfo, $BasedOn, $ReqDate, $AddDate, $Inventor, $accdate);

	if ($st->fetch()) {
		$st->close();

		$st = $db->prepare("select Name from User where UserID = ?");
		$st->bind_param('i', $Inventor);
		$st->execute();
		$st->bind_result($uname);
		$st->fetch();

		echo "<div id=ideadiv class=ideaboxtrans>\n" .
			"<input type=hidden name=id value=$ID>\n" .
			"<table border=0 class=highlight>\n" .

			"\t<tr><td>Name</td><td>$Name</td></tr>\n" .
			"\t<tr><td>Description</td><td>$Description</td></tr>\n" .
			"\t<tr><td id=idealeft>Status</td><td>$Status (<i>as of ". $StatusLastEdited ."</i>)</td></tr>\n";

		if (!empty($Cost)) echo "\t<tr><td>Cost</td><td>$Cost</td></tr>\n";
		if (!empty($AddInfo)) echo "\t<tr><td>Additional info</td><td>$AddInfo</td></tr>\n";
		if (!empty($BasedOn)) echo "\t<tr><td>Based on</td><td><a href=\"showIdea.php?id=$BasedOn\">$BasedOn</a></td></tr>\n";

		echo "\t<tr><td>Requested date</td><td>$ReqDate</td></tr>\n" .
			"\t<tr><td>Accepted date</td><td>$accdate</td></tr>\n" .
			"\t<tr><td>Added date</td><td>$AddDate</td></tr>\n" .
			"\t<tr><td>Category</td><td>$categoryString</td></tr>\n" .
			"\t<tr><td class=bottom>Inventor</td><td class=bottom><a href=\"showUser.php?id=$Inventor\">$uname</a>\t";

		if ($sess->isLoggedIn()) {
			// Userfollowing stuff:
			// This has to be done here in orded to have the button visible where it is now.
			require_once("DatabaseOperation/user.php");
			if (userIsFollowingUser($Inventor,  $userID)) {
				// ideaID also needs to be sent, it is used when userfollowing is stopped and the page reloads to show the correct idea again.
				echo "<span id='followUserButton' onmouseover='stopFollowingUserEff(" . $Inventor . ", " . $userID . ", " . $id .
					")' style='background-color:#66FF66;'>You are following this user.</span>";
				setLastSeenIdea($Inventor,  $userID);
			} else
				echo "<span id='followUserButton' onclick='userFollowUser(" . $Inventor . ", " . $userID . ", " . $id . ")'>Follow this user</span>";
		}

		echo "</td></tr>\n" .

// For lazy coders:
//			"\t<tr><td></td><td></td></tr>\n" .

			"</table>\n";

		// Idea editing button for inventor.
		if (canEdit($id, $userID) && !$isAdmin) {
			// Send idea-id along page change.
			echo "<hr><a href='editIdea.php?ideaid=$id'>Edit idea</a>";
			echo " &diams; ";
			echo "<a href='perms.php?id=$id'>Edit permissions</a>";
			echo " &diams; ";
			echo "<a href='showVersions.php?id=$id'>Show versions</a><br><br>";
		}
		// Idea editing button for adminz. 
		if ($isAdmin) {
			echo "<hr><a href='adminEditIdea.php?ideaid=$id'><br>Edit idea as admin</a>";
			echo " &diams; ";
			echo "<a href='perms.php?id=$id'>Edit permissions</a><br><br>";
			echo " &diams; ";
			echo "<a href='showVersions.php?id=$id'>Show versions</a><br><br>";
			echo "<form method=post action=showIdea.php?id=$id>";

			if ($Status == 'new')
				echo "<input type=submit name=accept value='Accept this idea'>";

			echo "<input type=submit name=delete value='Delete this idea'>";
			echo "</form>";
		}

		echo "</div><p>\n";

	}
	$db->close();
	
	// To allow inventor following in showIdea.php
	return $Inventor;
}


?>
