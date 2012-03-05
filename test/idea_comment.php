<html>
<head>
	<title>Ideabank - Add Idea</title>
</head>

<body>
<div id="addform">
	<form action="" method="post">
		Text: <input type="text" name="text" />
		commentorID: <input type="text" name="commentorID" />
		ideaID: <input type="text" name="ideaID" />
		<input type="submit" name ="submit" value="Submit" />
	</form> 
</div>
<?php
	error_reporting(E_ALL);	
	require_once('../DatabaseOperation/comment.php');
	
	if(isset($_POST['submit'])) {		
		addComment($_POST['text'], $_POST['commentorID'], $_POST['ideaID']);
		echo "jep.<br>";
	}
	
	getComments();
	
?>



</body>
</html>