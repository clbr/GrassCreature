<?php require_once("session.php"); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Ideabank</title>

	<link
	<?php
		require_once("getTheme.php");
		getTheme($sess->getUserID());
	?>
 rel="stylesheet" type="text/css">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<base target=main>
</head>

<body class=lining>

<h2>IdeaBank Statistics</h2>

<form method=post action=stats.php>

Starting from &nbsp; &nbsp;
<select name=period>
	<option value=30>30 days ago</option>
	<option value=60>60 days ago</option>
	<option value=90>90 days ago</option>
	<option value=180>six months ago</option>
	<option value=360>one year ago</option>
	<option value=300000>the start</option>
</select>

</form>

</body>
</html>
