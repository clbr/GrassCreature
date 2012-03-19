<?php
	error_reporting(E_ALL);	
	
	switch ($_POST['call'])
	{
		case 'sendComment':
			require_once("DatabaseOperation/comment.php");
			addComment($_POST['ideaid'], $_POST['userid'], $_POST['comment']);
			break;
	}
?>