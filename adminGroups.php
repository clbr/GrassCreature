<?php require_once("session.php");

$sess->mustBeLoggedIn();

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



<h2>
<?php
if ($sess->isAdmin())
	echo "Manage all groups";
else
	echo "Manage your groups";
?>
</h2>

<form action=adminGroups.php method=post class=indent>

<input type=button name=add value="Create new group">
<input type=button name=remove value="Delete group">
<input type=button name=members value="Edit group members">

<div id=groupsdiv>

</div>

<?php

require_once("DatabaseOperation/groups.php");
getGroups($sess->getUserID(), $sess->isAdmin());

?>

</form>


<script src="js/js.js" type="text/javascript"></script>
</body>
</html>
