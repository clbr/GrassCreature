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

	<link href="css/
	<?php
		require_once("getTheme.php");
		getTheme($sess->getUserID());
	?>
	.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<base target=main>
</head>

<body class=lining>

<?php

getUser($userID, $sess->isLoggedIn());

if ($userID == $sess->getUserID() || $sess->isAdmin()) {
	echo '<form method=POST action="editUser.php?userid='.$userID.'">
		<input type="submit" name="editUser" value="Edit details">
		</form>';
}

?>


<script src="js/js.js" type="text/javascript"></script>
</body>
</html>
