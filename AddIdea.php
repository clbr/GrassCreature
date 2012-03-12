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

<?php
	error_reporting(E_ALL);
	require_once('DatabaseOperation/idea.php');
	
	// Fields are shown when the page loads, after submit is pressed, fields go away and a success message is shown instead.
	if (!isset($_POST['submitIdea'])) {
	?>
	<div id="ideaForms" class="IdeaAdd">
		<form method="POST" action="AddIdea.php">
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
			Based on idea ID:<br>
			<input Name="BasedOn" rows="1" cols="20"><br>
			<input type="submit" name="submitIdea" value="Submit idea" onclick="ideaAdded()">
		</form> 	  
	</div>
	<?php
	}
	else {	
		addIdea($_POST['IdeaName'], $_POST['desc'], $_POST['ReqDate'], $_POST['CostEst'], $_POST['AddInfo'], $_POST['BasedOn'], $_SESSION['userID']);
		echo "<div class='IdeaAdd'>Idea succesfully added.</div>";
	}
?>

</body>
</html>