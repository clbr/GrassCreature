<?php
	error_reporting(E_ALL);

	require_once("details.php");

	function addComment ($ideaID, $commentorID, $text) {

		if (commentTooSoon($commentorID)) {
			$error["error"] = "You must wait 15s before posting new comments!";
			echo json_encode($error);
			return;
		}

		try {
			$pdo = pdo_connect();

			$sql = "INSERT INTO Comment (Text, User_UserID, Idea_IdeaID, Date) VALUES (:Text, :UserID, :IdeaID, NOW())";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':Text', $text);
			$stmt->bindParam(':UserID', $commentorID);
			$stmt->bindParam(':IdeaID', $ideaID);
			$stmt->execute();

			// Get the commentor's name and company and return them, so we can view
			// the comment immediately without reloading the whole page.
			$sql = "SELECT DATE_FORMAT(NOW(), '%d.%m.%Y %H:%i:%s') AS Date, Name, Company, DATE_FORMAT(NOW(), '%H%i%s') AS Rand
				FROM User
				WHERE UserID = :UserID";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':UserID', $commentorID);
			$stmt->execute();
			$pdo = null; // Close connection.

			$result = $stmt->fetch(PDO::FETCH_OBJ);

			//echo "<pre>"; var_dump(json_encode($result)); echo "</pre>";
			echo json_encode($result);
		}
		catch (PDOException $err)
		{
			echo $err;
		}
	}

	function deleteComment ($commentID) {
		$mysqli = db_connect();

		$sql = "DELETE FROM Comment WHERE CommentID = ?";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('i', $commentID);

		$stmt->execute();
	}

	function getComments($ideaID) {
		try {
			$pdo = pdo_connect();

			$sql = "SELECT CommentID, DATE_FORMAT(Date, '%d.%m.%Y %H:%i:%s') AS Date, Name, UserID, Company, Text
			FROM Comment
			LEFT OUTER JOIN User ON Comment.User_UserID = User.UserID
			WHERE Idea_IdeaID = $ideaID";

			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':IdeaID', $ideaID);
			$stmt->execute();

			return $stmt;
		}
		catch (PDOException $err)
		{
			echo $err;
		}
	}

// Return true if the last comment was made less than 15s ago
function commentTooSoon($uid) {

	$db = db_connect();

	$st = $db->prepare("select unix_timestamp()-unix_timestamp(Date) from Comment where " .
				"User_UserID=? order by Date desc limit 1") or die($db->error);

	$st->bind_param("i", $uid);
	$st->execute();
	$st->bind_result($secs);

	if ($st->fetch()) {
		if ($secs > 15)
			return false;

		return true;
	}

	return false;
}

?>
