<?php

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


?>
