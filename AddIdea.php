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
</head>

<body>

<div id="Add" class="IdeaAdd">
	<form method="POST" action="AddIdea.php">
		<div><label for="name">*Idea name:</label> <input type="text" id="IdeaName" name="IdeaName"/></div>
		<div><label for="desc">*Idea description: </label> <TEXTAREA name="desc" rows="4" cols="20"></TEXTAREA></div>
		<div><label for="cost">Cost estimation (&euro;): </label> <input type="text" id="CostEst" name="CostEst"></div>
		<div><label for="info">Additional information: </label> <TEXTAREA Name="AddInfo" rows="3" cols="20"></TEXTAREA></div>
		<div><label for="info">Request date: </label> <TEXTAREA Name="ReqDate" rows="1" cols="20"></TEXTAREA></div>
		<div><label for="based">Based on: </label> <TEXTAREA Name="BasedOn" rows="1" cols="20"></TEXTAREA></div>
		<div><label for="based">ID:ni on: </label> <TEXTAREA Name="userID" rows="1" cols="20"></TEXTAREA></div>
		<div class="submit"><input type="submit" name="submitIdea" value="Submit idea"></div>
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

<script src="js/js.js" type="text/javascript"></script>
</body>
</html>