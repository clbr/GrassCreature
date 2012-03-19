<?php require_once("session.php");
require_once("DatabaseOperation/idea.php");

if (!isset($_GET["id"])) {
	echo "<script type=\"text/javascript\">" .
		"alert(\"No idea id given\"); window.history.back();" .
		"</script>";
	return;
}

require_once("DatabaseOperation/idea.php");

$id = $_GET["id"];

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

$uid = -1;
if ($sess->isLoggedIn())
	$uid = $sess->getUserID();

/* Show the actual idea */

getIdea($id, $uid, $sess->isAdmin());

/* Attached images handling */

if (file_exists("userImages/$id")) {
	echo "<div id=attachments>\n";
	echo "<h3>Attachments:</h3>\n";

	$dir = opendir("userImages/$id");

	// Read every filename in this directory
	while (($file = readdir($dir))) {

		// If the file starts with a dot, skip it
		if (strncmp($file, ".", 1) == 0) continue;

		// In megabytes
		$size = filesize("userImages/$id/$file") / 1024 / 1024.0;

		echo "<a href='userImages/$id/$file' target=_blank>$file, ";
		printf("%.3f", $size);
		echo " MB</a><br>";
	}

	closedir($dir);

	echo "</div>\n";
}

/* Comments listing and commenting */

require_once("DatabaseOperation/comment.php");
$comments = getComments($id);

while ($comment = $comments->fetch_object()) {
	echo "<div id=comment" . $comment->CommentID . " class='comment'>" . $comment->Date . " " .
	"<a href='showUser.php?id=" . $comment->UserID . "'>" . $comment->Name . "</a>";
	if ($comment->Company != "") { echo ", " . $comment->Company; } 
	echo "<br><hr class='shortline'><br>" . $comment->Text . "<br></div>";
}

echo "<input type='button' value='Comment...' onclick=showCommentForm(" . $id . ")>";

/* Rating stuff */

if (isset($_POST['Yes'])) {
	addVote(1, $id, $uid);
} else if (isset($_POST['No'])) {
	addVote(-1, $id, $uid);
}


echo "<div id='rating'>";
echo "Rating: " . getVote($id);
echo "</div>";


echo "<form method='post' action='showIdea.php?id=$id'>";
echo "<input type='submit' name='Yes' value='Vote Up'>";
echo "<input type='submit' name='No' value='Vote Down'>";
echo "</form>";

?>

<script src="js/js.js" type="text/javascript"></script>
</body>
</html>
