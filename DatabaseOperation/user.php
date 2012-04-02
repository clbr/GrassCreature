<?php

error_reporting(E_ALL);
require_once('details.php');
require_once('follow.php');

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

function editUser($userID, $email, $company, $compAddr, $theme)
{
	try
	{
		$pdo = pdo_connect();

		$sql = "UPDATE User SET Email = :Email, Company = :Company, CompanyAddress = :CompAddr, SelectedTheme = :Theme WHERE UserID = :UserID";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':UserID', $userID);
		$stmt->bindParam(':Email', $email);
		$stmt->bindParam(':Company', $company);
		$stmt->bindParam(':CompAddr', $compAddr);
		$stmt->bindParam(':Theme', $theme);
		$stmt->execute();

		$pdo = null; // Close connection.
	}
	catch (PDOException $err)
	{
		echo $err;
	}
}

function getUserData($userID)
{
	try
	{
		$pdo = pdo_connect();

		$sql = "SELECT * FROM User WHERE UserID = :UserID";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':UserID', $userID);
		if ($stmt->execute())
			return $stmt;
		$pdo = null; // Close connection.
	}
	catch (PDOException $err)
	{
		echo $err;
	}
}

function getUserTheme($userID)
{
	try
	{
		$pdo = pdo_connect();

		$sql = "SELECT SelectedTheme FROM User WHERE UserID = :UserID";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':UserID', $userID);
		if ($stmt->execute()) {
			if ($theme = $stmt->fetch(PDO::FETCH_OBJ))
				return $theme->SelectedTheme;
		}

		$pdo = null; // Close connection.
	}
	catch (PDOException $err)
	{
		echo $err;
	}
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
			$sql = "SELECT COUNT(CommentID) AS Count, CommentID, Name
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
				$iwnc[] = array('ideaID' => (int)$followed_idea->Followed_IdeaID, 'ideaname' => $newcomments->Name,
					'comments' => (int)$newcomments->Count, 'commentID' => (int)$newcomments->CommentID);
		}

		$pdo = null; // Close connection.
		//echo "<pre>"; var_dump(json_encode($iwnc)); echo "</pre><br>";
		echo json_encode($iwnc);
	}
	catch (PDOException $err) {
		echo $err;
	}
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
			$sql = "SELECT IdeaID, Idea.Name AS Ideaname, UserID, User.Name as Username
				FROM Idea
				LEFT OUTER JOIN User
				ON Idea.Inventor = User.UserID
				WHERE Inventor = :Stalked AND Status = 'active' AND IdeaID >
					(SELECT Last_IdeaID
					FROM User_has_follower
					WHERE StalkerID = :UserID AND StalkedID = :Stalked)";
			$stmt2 = $pdo->prepare($sql);
			$stmt2->bindParam(':Stalked', $followed_user->StalkedID);
			$stmt2->bindParam(':UserID', $userID);
			$stmt2->execute();

			// Needs a loop, because one inventor can have more than one new ideas added!!
			while ($newideas = $stmt2->fetch(PDO::FETCH_OBJ)) {
				//echo $newideas->Count . "<br>";
				if (isset($newideas->IdeaID))
					$uwni[] = $newideas;
			}

				/*array('StalkedID' => (int)$followed_user->StalkedID, 'username' => $newideas->Username, 'userID' => $newideas->UserID,
					'ideas' => (int)$newideas->Count, 'ideaID' => (int)$newideas->IdeaID, 'ideaname' => $newideas->Ideaname);*/
		}

		$pdo = null; // Close connection.
		//echo "<pre>"; var_dump($uwni); echo "</pre><br>";
		echo json_encode($uwni);
	}
	catch (PDOException $err) {
		echo $err;
	}
}


function listUsers() {

	$db = db_connect();

	$st = $db->query("select Name from User") or die($db->error);

	while ($row = $st->fetch_row())
		echo "$row[0]\n";

	$db->close();
}

?>
