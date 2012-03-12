<?php require_once("session.php"); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Ideabank</title>

	<link href="css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<base target=main>
</head>

<body>


<h1 id=frontwelcome>Welcome to the Ideabank!</h1>


<?php

require_once("DatabaseOperation/accept.php");

if ($sess->isAdmin()) {

	$num = countNewIdeas();

	if ($num > 0) {
		echo "<div id=adminnote class=ideabox>\n" .
			"<a href=\"accept.php\">" .
			"You have $num new ideas to accept.</a><br>" .
			"</div><p>\n";
	}

}

// Link for adding new ideas.
if ($sess->isLoggedIn()) {
	echo "<div id=ideaAddingPage class=ideabox>
	<a href='http://student.labranet.jamk.fi/~f6855/ideapankkikehitys/GrassCreature/AddIdea.php'>Add idea</a>
	</div>";
}

?>

<div id=newestideas class=ideabox>
Top 10 newest ideas here
</div>

<div id=bestideas class=ideabox>
Top 10 best ideas here
</div>

<div id=mostcommentedideas class=ideabox>
Top 10 most commented ideas here
</div>


<div id=frontsearch class=ideabox>
<form method=post action="search.php">
	<input type=text size=30 name=simpletext> <input type=submit value=Search><br>
	<a href="search.php">Advanced search</a>
</form>
</div>



<script src="js/js.js" type="text/javascript"></script>
</body>
</html>
