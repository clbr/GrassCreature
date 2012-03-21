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
require_once("Top10.php");

if ($sess->isAdmin()) {

	$num = countNewIdeas();

	if ($num > 0) {
		echo "<div id=adminnote class=ideabox>\n" .
			"<a href=\"accept.php\">" .
			"You have $num new ideas to accept.</a><br>" .
			"</div><p>\n";
	}

}

?>

<div id=newestideas class=ideabox>
<h3>Top 10 newest ideas</h3>
<?php
top10_newest();
?>
</div>

<div id=bestideas class=ideabox>
<h3>Top 10 best ideas</h3>
<?php
top10_rated();
?>
</div>

<div id=mostcommentedideas class=ideabox>
<h3>Top 10 most commented ideas</h3>
<?php
top10_commented();
?>
</div>


<script src="js/js.js" type="text/javascript"></script>
</body>
</html>
