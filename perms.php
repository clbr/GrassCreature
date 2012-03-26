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


<script src="js/js.js" type="text/javascript"></script>
</body>
</html>
