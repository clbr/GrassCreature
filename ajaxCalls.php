<?php
	error_reporting(E_ALL);

	switch ($_POST['call'])
	{
		case 'sendComment':
			require_once("DatabaseOperation/comment.php");
			require_once("DatabaseOperation/idea.php");
			require_once("DatabaseOperation/user.php");
			$comment = htmlspecialchars($_POST['comment']);
			addComment($_POST['ideaid'], $_POST['userid'], $comment);
			setLastSeenComment($_POST['ideaid'], $_POST['userid']);
			break;

		// Idea following.
		case 'userFollowIdea':
			require_once("DatabaseOperation/user.php");
			userFollowIdea($_POST['ideaid'], $_POST['userid']);
			break;
		case 'stopFollowingIdea':
			require_once("DatabaseOperation/user.php");
			stopFollowingIdea($_POST['ideaid'], $_POST['userid']);
			break;
		case 'followedIdeas':
			require_once("DatabaseOperation/user.php");
			getNewComments($_POST['userid']);
			break;

		// User following.
		case 'userFollowUser':
			require_once("DatabaseOperation/user.php");
			userFollowUser($_POST['stalkedid'], $_POST['userid']);
			break;
		case 'stopFollowingUser':
			require_once("DatabaseOperation/user.php");
			stopFollowingUser($_POST['stalkedid'], $_POST['userid']);
			break;
		case 'followedUsers':
			require_once("DatabaseOperation/user.php");
			getNewIdeas($_POST['userid']);
			break;		

		case 'groups':
			require_once("DatabaseOperation/groups.php");
			listGroups();
			break;
		case 'users':
			require_once("DatabaseOperation/user.php");
			listUsers();
			break;
	}
?>
