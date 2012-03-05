<html>
<head>
	<title>Ideabank - Add Idea</title>

	<link href="css/idea_add.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>

<body>
<div id="addform">
	<form name="input" action="swl/idea_add.php" method="post">
		Text: <input type="text" name="text" />
		commentorID: <input type="text" name="commentorID" />
		ideaID: <input type="text" name="ideaID" />
		<input type="submit" value="Submit" />
	</form> 
</div>
<?php
	require_once('../DatabaseOperation/idea.php');
	if(isset($_POST['submit']))
		addComment($_POST['text'], $_POST['commentorID'], $_POST['ideaID']);
	echo "jep.<br>";
?>



</body>
</html>