<?php

require_once("session.php");
require_once("DatabaseOperation/accept.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Ideabank - Accept Ideas</title>

	<link href="css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
</head>

<body>

<form method="post" action="accept.php">

<?php
if(isset($_POST['Accept'])){
	acceptSelected();
} else if(isset($_POST['Delete'])){
	deleteSelected();
}
getUnaccepted();
?>

<input type="submit" name="Accept" value="Accept">
<input type="submit" name="Delete" value="Delete">
</form>

</body>
</html>
