<?php require_once("session.php");

$sess->mustBeLoggedIn();

if (!isset($_GET["id"])) {
	echo "<script type=\"text/javascript\">" .
		"alert(\"No idea id given\"); window.history.back();" .
		"</script>";
	return;
}

$id = $_GET["id"];

require_once("DatabaseOperation/idea.php");

if (!$sess->isAdmin() && getIdeaInventor($id) != $sess->getUserID()) {
	echo "<script type=\"text/javascript\">" .
		"alert(\"You don't have rights to edit the permissions of this idea.\"); window.history.back();" .
		"</script>";
	return;
}

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

<h2>Edit permissions for
<?php
echo getIdeaName($id);
?>
</h2>

<?php
echo "<form id=permform method=post action='perms.php?id=$id'>";
?>

<input type=button name=addgroup value="Add group to permission list" onclick='addgrp()'>
<input type=hidden name=group>
<input type=submit name=save value="Save">
<br><br>

<?php
getIdeaPermissions($id);
?>

<br><br>
<input type=submit name=save value="Save">

</form>

<script src="js/js.js" type="text/javascript"></script>
</body>
</html>
