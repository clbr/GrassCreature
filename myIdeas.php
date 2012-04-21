<?php require_once("session.php");

// This page requires login.
$sess->mustBeLoggedIn();
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
	<script src="js/js.js" type="text/javascript"></script>
</head>

<body class=lining>
<div class=middle>
<?php
	error_reporting(E_ALL);
	require_once('DatabaseOperation/idea.php');

	$myIdeas = getMyIdeas($sess->getUserID());

	// First check if query result contains anything.
	if ($myIdeas->rowCount() > 0) {
		echo "<table>";
		while ($idea = $myIdeas->fetch(PDO::FETCH_OBJ)) {
			echo "<tr><td>" . $idea->AddingDate . "</td><td><a href='showIdea.php?id=" . $idea->IdeaID . "'>" . $idea->Name . "</a>" .
			"</td><td>" . $idea->Status . "</td></tr>";
		}
		echo "</table>";
	}
	else {
		echo "You have not added any ideas yet.";
	}
?>
</div>
</body>
</html>
