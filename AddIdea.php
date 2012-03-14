<?php require_once("session.php");

// This page requires login.
if (!$sess->isLoggedIn())
	header("Location: index.php");
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

<body>

<?php
	error_reporting(E_ALL);
	require_once('DatabaseOperation/idea.php');
	require_once('uploadFile.php');

	if (!isset($_POST['submitIdea'])) {
	// Fields are shown when the page loads, after submit is pressed, fields go away and a success message is shown instead.
	?>
	<div id="ideaForms" class="IdeaAdd">
		<form method="POST" action="AddIdea.php" enctype="multipart/form-data">
			*Idea name:<br>
			<input type="text" id="IdeaName" name="IdeaName"><br>

			*Idea description:<br>
			<TEXTAREA name="desc" rows="10" cols="70"></TEXTAREA><br>

			Cost estimation (&euro;)<br>
			<input type="text" id="CostEst" name="CostEst"><br>

			Additional information:<br>
			<TEXTAREA Name="AddInfo" rows="6" cols="70"></TEXTAREA><br>

			Request date/time frame for idea/implementation:<br>
			<input Name="ReqDate" rows="1" cols="20"><br>

			Based on idea ID (if any):<br>
			<input Name="BasedOn" rows="1" cols="20"><br>

			Attach images:<br>
			<div id=addimages>
			<input type="file" name="file"></div>
			<br>
			<input type="submit" name="submitIdea" value="Submit idea">
		</form>
	</div>
	<?php
	}
	else {
		$ideaID = addIdea($_POST['IdeaName'], $_POST['desc'], $_POST['ReqDate'], $_POST['CostEst'], $_POST['AddInfo'], $_POST['BasedOn'], $sess->getUserID());

		// Upload image if there are any.
		if (!$_FILES['file']['size'] == 0)
			uploadImage($ideaID);

		echo "<div class='IdeaAdd'>Idea succesfully added with the ID: $ideaID.</div>";
	}
?>

<script src="js/js.js" type="text/javascript"></script>

<script type="text/javascript">

function moreimages() {

	var send = document.getElementById('addimages');
	if (!send) return;

	var br = document.createElement('br');
	send.appendChild(br);

	var inp = document.createElement('input');
	inp.type = 'file';
	inp.name = 'file[]';
	inp.onchange = 'moreimages()';
	send.appendChild(inp);
}

</script>

</body>
</html>
