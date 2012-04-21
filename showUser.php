<?php require_once("session.php");

if (!isset($_GET["id"])) {
	echo "<script type=\"text/javascript\">" .
		"alert(\"No user id given\"); window.history.back();" .
		"</script>";
	return;
}

require_once("DatabaseOperation/user.php");

$userID = $_GET["id"];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Ideabank</title>

	<link
	<?php
		require_once("getTheme.php");
		getTheme();
	?>
 rel="stylesheet" type="text/css">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<base target=main>
</head>

<body class=lining>

<?php

if ($sess->isLoggedIn() && ($userID == $sess->getUserID() || $sess->isAdmin())) {
	echo '<form method=POST action="editUser.php?userid='.$userID.'">
		<input type="submit" name="editUser" value="Edit details">
		</form>';

	/* Print followed ideas. */
	require_once("DatabaseOperation/follow.php");
	$followed_ideas = getFollowedIdeas($userID);
	
	if ($followed_ideas->rowCount() > 0) {
		echo "<div id=myFollowedIdeas class=ideaboxtrans>Followed ideas:<br><table class=highlight>";
		while ($idea = $followed_ideas->fetch(PDO::FETCH_OBJ)) {
			//echo "<pre>".var_dump($idea)."</pre>";
			echo "<tr><td><a href='showIdea.php?id=$idea->IdeaID'>$idea->Name</a></td></tr>";
		}
		echo "</table></div>";
	}

	/* Print followed users. */
	$followed_users= getFollowedUsers($userID);
	
	if ($followed_users->rowCount() > 0) {
		echo "<div id=myFollowedUsers class=ideaboxtrans>Followed users:<br><table class=highlight style='width:100%'>";
		while ($user = $followed_users->fetch(PDO::FETCH_OBJ)) {
			//echo "<pre>".var_dump($idea)."</pre>";
			echo "<tr><td><div class=center><a href='showUser.php?id=$user->StalkedID'>$user->Name</a></div></td></tr>";
		}
		echo "</table></div>";
	}
	
	
}

getUser($userID, $sess->isLoggedIn());


?>


<script src="js/js.js" type="text/javascript"></script>
</body>
</html>
