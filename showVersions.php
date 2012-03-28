<?php
require_once("session.php");

if (!isset($_GET["id"])) {
	echo "<script type=\"text/javascript\">" .
		"alert(\"No idea id given\"); window.history.back();" .
		"</script>";
	return;
}

$ideaID = $_GET["id"];

?>

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

<body class=lining>

<?php

$uid = -1;
if ($sess->isLoggedIn())
	$uid = $sess->getUserID();

/* Get old versions of the idea*/

if ($sess->isAdmin()) {
	require_once("DatabaseOperation/idea.php");
	$versions = getVersions($ideaID);
	echo "<pre>"; var_dump($versions); echo "</pre><br><br>";
	echo $versions[0]->Name;
	
}





?>

<script src="js/js.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js" type="text/JavaScript"></script>
<script type="text/JavaScript"></script>
</body>
</html>
