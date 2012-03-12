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
	<script src="js/js.js" type="text/javascript"></script>
</head>

<body>

<div id="Add" class="IdeaAdd">
	<form method="POST" action="AddIdea.php">
		<label for="name">*Idea name:</label><br>
		<input type="text" id="IdeaName" name="IdeaName"><br>
		<label for="desc">*Idea description: </label><br>
		<TEXTAREA name="desc" rows="10" cols="70"></TEXTAREA><br>
		<label for="cost">Cost estimation (&euro;): </label><br>
		<input type="text" id="CostEst" name="CostEst"><br>
		<label for="info">Additional information: </label><br>
		<TEXTAREA Name="AddInfo" rows="3" cols="20"></TEXTAREA><br>
		<label for="ReqDate">Request date: </label><br>
		<input Name="ReqDate" rows="1" cols="20"><br>
		<label for="based">Based on: </label><br>
		<input Name="BasedOn" rows="1" cols="20"><br>
		<label for="userID">ID:ni on: </label><br>
		<input Name="userID" rows="1" cols="20"><br>
		<input type="submit" name="submitIdea" value="Submit idea">
	</form> 	  
</div>

<?php
	error_reporting(E_ALL);
	require_once('DatabaseOperation/idea.php');
	
	if (isset($_POST['submitIdea'])) {
		addIdea($_POST['IdeaName'], $_POST['desc'], $_POST['ReqDate'], $_POST['CostEst'], $_POST['AddInfo'], $_POST['BasedOn'], $_POST['userID']);
		echo "painettu";
	}
?>

</body>
</html>