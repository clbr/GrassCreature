<?php
	error_reporting(E_ALL);

	require_once("details.php");

	function addComment ($text, $commentorID, $ideaID) {
		$mysqli = db_connect();

		$sql = "INSERT INTO Comment (Text, User_UserID, Idea_IdeaID, Date) VALUES (?, ?, ?, NOW())";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('sii', $text, $commentorID, $ideaID);

		$stmt->execute();
	}
	
	function deleteComment ($commentID) {
		$mysqli = db_connect();

		$sql = "DELETE FROM Comment WHERE CommentID = ?";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('i', $commentID);

		$stmt->execute();
	}

	function getComments($ideaID) {
		$mysqli = db_connect();

		// If there are column name collisions, use User.Name etc. instead.
		$sql = "SELECT CommentID, DATE_FORMAT(Date, '%d.%m.%Y %H:%i:%s') AS Date, Name, UserID, Company, Text FROM Comment LEFT OUTER JOIN User ON Comment.User_UserID = User.UserID WHERE Idea_IdeaID = $ideaID";
		$result = $mysqli->query($sql) or die($mysqli->error);

		return $result;
	}
?>