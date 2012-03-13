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
</head>

<body>

<?php
	$sess->printBox();
?>

<div id=rightbar>
	<div id=frontsearch>
	<form method=post action="search.php">
		<input type=text size=30 name=simpletext> <input type=submit value=Search><br>
		<a href="search.php">Advanced search</a>
	</form>
	</div>

</div>

<iframe name=main id=main src="front.php"></iframe>

<script src="js/js.js" type="text/javascript"></script>
</body>
</html>
