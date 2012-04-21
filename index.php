<?php require_once("session.php"); ?>
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
</head>

<body style="margin-right: 2em;">
<img id=frontpic src='img/ideabankheader.png' alt=ideabank>
<?php
	$sess->printBox();
?>

<div id=rightbar>

	<div class=rightbox>
	<a href=front.php>
	<img src="img/logo.png" alt=logo height=37 width=139>
	</a>
	</div><p>

	<div id=frontsearch class=rightbox>
	<form method=post action="search.php" target=main>
		<input type=text size=18 name=simpletext> <input type=submit value=Go style="width: 2.5em;"><br>
		<a href="search.php">Advanced search</a>
	</form>
	</div><p>

	<?php
	// Link for adding new ideas.
	if ($sess->isLoggedIn()) {
		echo "<div class=rightbox>" .
		"<a href='addIdea.php'>Add idea</a>" .
		"</div><p>\n";

		echo "<div class=rightbox>" .
		"<a href='myIdeas.php'>My ideas</a>" .
		"</div><p>\n";

		echo "<div class=rightbox>" .
		"<a href='adminGroups.php'>Manage groups</a>" .
		"</div><p>\n";

		$uid = $sess->getUserID();

		echo "<div class=rightbox>" .
		"<a href='showUser.php?id=$uid'>My profile</a>" .
		"</div><p>\n";
	} else {
		echo "<div class=rightbox>" .
		"<a href='register.php'>Register</a></div><p>\n";
	}
	?>

	<div class=rightbox>
	<a href='stats.php'>Statistics</a>
	</div><p>


</div>

<iframe name=main id=main src="front.php"></iframe>
<img id=frontpic2 src='img/ideabankbottom.png' alt=ideabank>
<script src="js/js.js" type="text/javascript"></script>
<script type="text/javascript">

function dolinks() {
	var links = document.getElementsByTagName('a');
	var len = links.length;

	for (var i = 0; i < len; i++) {
		links[i].target = 'main';
	}
}

dolinks();

</script>

</body>
</html>
