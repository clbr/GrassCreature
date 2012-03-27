<?php

error_reporting(E_ALL);
require_once('details.php');

function getUser($id, $isLoggedIn) {

	$db = db_connect();

	$st = $db->prepare("select Name, Company, CompanyAddress from User where UserID = ?");
	$st->bind_param("i", $id);

	$st->execute();

	$st->bind_result($name, $co, $coadd);
	if ($st->fetch()) {

		$last = "";

		if (empty($co) && empty($coadd))
			$last = "class=bottom";

		echo "<div id=userdiv class=ideaboxtrans>\n" .
			"<input type=hidden name=id value=$id>\n" .
			"<table border=0 width='100%' class=highlight>\n" .

			"\t<tr><td $last>Name</td><td $last>$name</td></tr>\n";

		$last = "";

		if (empty($coadd))
			$last = "class=bottom";

		if (!empty($co)) echo "\t<tr><td $last>Company</td><td $last>$co</td></tr>\n";
		if (!empty($coadd)) echo "\t<tr><td class=bottom>Additional company info</td><td class=bottom>$coadd</td></tr>\n";

		echo "</table>\n";

	}
	$st->close();

	// Only show ideas to logged in users
	if ($isLoggedIn) {

		$st = $db->prepare("select IdeaID, Name from Idea where Inventor = ?");
		$st->bind_param("i", $id);

		$st->execute();

		$st->bind_result($ideaid, $ideaname);

		$st->store_result();
		$rows = $st->num_rows;

		if ($rows > 0) {

			echo "<br><hr><br>\n";

			echo "<table border=0 width='100%' class=highlight>\n" .
				"<tr><th>Ideas added by $name</th></tr>";

			for ($i = 1; $st->fetch(); $i++) {

				$last = "";
				if ($i == $rows)
					$last = "class=bottom";

				echo "\t<tr><td $last><a href=\"showIdea.php?id=$ideaid\">$ideaname</a></td></tr>\n";
			}

			echo "</table>\n";
		}
	}

	echo "</div>\n";

	$db->close();
}

function userFollowIdea($ideaID, $userID) {
	$mysqli = db_connect();

	$sql = "INSERT INTO Idea_has_follower(FollowerID, Followed_IdeaID, Last_CommentID)
		VALUES(?, ?,
		(SELECT CommentID
		FROM Comment
		WHERE Idea_IdeaID = ?
		ORDER BY CommentID DESC
		LIMIT 1))";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param('iii', $userID, $ideaID, $ideaID);
	$stmt->execute();
}

function stopFollowingIdea($ideaID, $userID) {
	$mysqli = db_connect();

	$sql = "DELETE FROM Idea_has_follower WHERE FollowerID = ? AND Followed_IdeaID = ?";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param('ii', $userID, $ideaID);
	$stmt->execute();
}

function userIsFollowingIdea($ideaID, $userID) {
	$mysqli = db_connect();

	$sql = "SELECT EXISTS(SELECT 1 FROM Idea_has_follower WHERE FollowerID = ? AND Followed_IdeaID = ?)";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param('ii', $userID, $ideaID);
	$stmt->execute();
	$stmt->bind_result($result);
	$stmt->fetch();

	return $result;
}

function setLastSeenComment($ideaID, $userID) {
	$mysqli = db_connect();

	$sql = "UPDATE Idea_has_follower SET Last_CommentID =
		(SELECT CommentID
		FROM Comment
		WHERE Idea_IdeaID = ?
		ORDER BY CommentID DESC
		LIMIT 1)
		WHERE FollowerID = ? AND Followed_IdeaID = ?";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param('iii', $ideaID, $userID, $ideaID);
	$stmt->execute();
}

function getNewComments($userID) {
	try {
		$pdo = pdo_connect();

		// Get all the ideas that the user is following.
		$sql = "SELECT Followed_IdeaID
			FROM Idea_has_follower
			WHERE FollowerID = :UserID";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':UserID', $userID);
		$stmt->execute();

		// Ideas with new comments, duh!
		$iwnc = array();
		
		// Check which of the followed ideas have had new comments since last viewing the idea.
		while ($followed_idea = $stmt->fetch(PDO::FETCH_OBJ)) {
			$sql = "SELECT COUNT(CommentID) AS Count, Name
				FROM Comment
				LEFT OUTER JOIN Idea
				ON Comment.Idea_IdeaID = Idea.IdeaID
				WHERE Idea_IdeaID = :Followed_idea AND CommentID >
					(SELECT Last_CommentID
					FROM Idea_has_follower
					WHERE FollowerID = :UserID AND Followed_IdeaID = :Followed_idea)";
			$stmt2 = $pdo->prepare($sql);
			$stmt2->bindParam(':Followed_idea', $followed_idea->Followed_IdeaID);
			$stmt2->bindParam(':UserID', $userID);
			$stmt2->execute();

			$newcomments = $stmt2->fetch(PDO::FETCH_OBJ);
			//echo $newcomments->Count . "<br>";
			if ($newcomments->Count > 0)
				$iwnc[] = array('ideaID' => (int)$followed_idea->Followed_IdeaID, 'ideaname' => $newcomments->Name, 'comments' => (int)$newcomments->Count);
		}

		$pdo = null; // Close connection.
		//echo "<pre>"; var_dump(json_encode($iwnc)); echo "</pre><br>";
		echo json_encode($iwnc);
	}
	catch (PDOException $err) {
		echo $err;
	}
}

function userFollowUser($stalkedID, $userID) {
	$mysqli = db_connect();

	$sql = "INSERT INTO User_has_follower(StalkerID, StalkedID, Last_IdeaID)
		VALUES(?, ?,
		(SELECT IdeaID
		FROM Idea
		WHERE Inventor = ?
		ORDER BY IdeaID DESC
		LIMIT 1))";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param('iii', $userID, $stalkedID, $stalkedID);
	$stmt->execute();
}

function stopFollowingUser($stalkedID, $userID) {
	$mysqli = db_connect();

	$sql = "DELETE FROM User_has_follower WHERE StalkerID = ? AND StalkedID = ?";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param('ii', $userID, $stalkedID);
	$stmt->execute();
}

function userIsFollowingUser($stalkedID, $userID) {
	$mysqli = db_connect();

	$sql = "SELECT EXISTS(SELECT 1 FROM User_has_follower WHERE StalkerID = ? AND StalkedID = ?)";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param('ii', $userID, $stalkedID);
	$stmt->execute();
	$stmt->bind_result($result);
	$stmt->fetch();

	return $result;
}

function setLastSeenIdea($stalkedID, $userID) {
	$mysqli = db_connect();

	$sql = "UPDATE User_has_follower SET Last_IdeaID =
		(SELECT IdeaID
		FROM Idea
		WHERE Inventor = ?
		ORDER BY IdeaID DESC
		LIMIT 1)
		WHERE StalkerID = ? AND StalkedID = ?";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param('iii', $stalkedID, $userID, $stalkedID);
	$stmt->execute();
}

function getNewIdeas($userID) {
	try {
		$pdo = pdo_connect();

		// Get all the users that the user is following.
		$sql = "SELECT StalkedID
			FROM User_has_follower
			WHERE StalkerID = :UserID";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':UserID', $userID);
		$stmt->execute();

		// Users with new ideas, duh!
		$uwni = array();

		// Check which of the followed users have new ideas added.
		while ($followed_user = $stmt->fetch(PDO::FETCH_OBJ)) {
			$sql = "SELECT Idea.IdeaID AS IdeaID, Idea.Name AS Ideaname, User.UserID AS UserID, User.Name as Username
				FROM Idea
				LEFT OUTER JOIN User
				ON Idea.Inventor = User.UserID
				WHERE Inventor = :Stalked AND IdeaID >
					(SELECT Last_IdeaID
					FROM User_has_follower
					WHERE StalkerID = :UserID AND StalkedID = :Stalked)";
			$stmt2 = $pdo->prepare($sql);
			$stmt2->bindParam(':Stalked', $followed_user->StalkedID);
			$stmt2->bindParam(':UserID', $userID);
			$stmt2->execute();

			$newideas = $stmt2->fetch(PDO::FETCH_OBJ);
			//echo $newideas->Count . "<br>";
			if (isset($newideas->IdeaID))
				$uwni[] = $newideas;
				
				/*array('StalkedID' => (int)$followed_user->StalkedID, 'username' => $newideas->Username, 'userID' => $newideas->UserID,
					'ideas' => (int)$newideas->Count, 'ideaID' => (int)$newideas->IdeaID, 'ideaname' => $newideas->Ideaname);*/			
		}

		$pdo = null; // Close connection.
		//echo "<pre>"; var_dump(json_encode($uwni)); echo "</pre><br>";
		echo json_encode($uwni);
	}
	catch (PDOException $err) {
		echo $err;
	}
}


?>
