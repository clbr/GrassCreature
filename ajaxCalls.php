<?php
	error_reporting(E_ALL);

	switch ($_POST['call'])
	{
		case 'sendComment':
			require_once("DatabaseOperation/comment.php");
			require_once("DatabaseOperation/idea.php");
			addComment($_POST['ideaid'], $_POST['userid'], $_POST['comment']);
			setLastSeenComment($_POST['ideaid'], $_POST['userid']);
			break;
		case 'userFollowIdea':
			require_once("DatabaseOperation/idea.php");
			userFollowIdea($_POST['ideaid'], $_POST['userid']);
			break;
		case 'followedIdeas':
			require_once("DatabaseOperation/idea.php");
			getNewComments($_POST['userid']);
			break;		
	}
?>