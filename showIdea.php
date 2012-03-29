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

if ($sess->isAdmin()) {
	require_once("DatabaseOperation/accept.php");

	/* Admin delete/accept actions */
	if (isset($_POST["accept"])) {
		acceptIdea($id);
	} else if (isset($_POST["delete"])) {
		deleteIdea($id);
	}
}

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

<?php

$uid = -1;
if ($sess->isLoggedIn())
	$uid = $sess->getUserID();

/* Notification if coming from changing versions */

if ($sess->isAdmin() && isset($_GET['versionChanged']))
	echo "<span id='versionChanged'>The idea has been reverted to version " . $_GET['versionChanged'] . ".</span><br>";

/* Show the actual idea */

$inventor = getIdea($id, $uid, $sess->isAdmin());

/* Ratings */
echo "<div id='rating' class=ideabox>";
if ($sess->isLoggedIn()) {
	echo "<form method='post' action='showIdea.php?id=$id'>\n";
	echo "<button name='Yes'><img src='img/up.png' width=32 height=32></button>\n";
	echo "<button name='No'><img src='img/down.png' width=32 height=32></button>\n";
	echo "</form>\n";

	/* Rating stuff */

	if (isset($_POST['Yes'])) {
		addVote(1, $id, $uid);
	} else if (isset($_POST['No'])) {
		addVote(-1, $id, $uid);
	}

}
echo "Rating: " . getVote($id);
echo "</div>\n";

/* Idea following stuff */

if ($sess->isLoggedIn()) {
	require_once("DatabaseOperation/user.php");
	if (userIsFollowingIdea($id,  $sess->getUserID())) {
		echo "<span id='followIdeaButton' onmouseover='stopFollowingIdeaEff(" . $id . ", " . $sess->getUserID() . ")' style='background-color:#66FF66;'>You are following this idea.</span>";
		setLastSeenComment($id,  $sess->getUserID());
	}
	else
		echo "<span id='followIdeaButton' onclick='userFollowIdea(" . $id . ", " . $sess->getUserID() . ")'>Follow this idea</span>";
}

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

	echo "</div><br>\n";
}

/* Comments listing and commenting */

require_once("DatabaseOperation/comment.php");
$comments = getComments($id);

echo "<div id='commentsArea'>";
while ($comment = $comments->fetch_object()) {
	echo "<div id=comment" . $comment->CommentID . " class='comment'>" . $comment->Date . " " .
	"<a href='showUser.php?id=" . $comment->UserID . "' name=" . $comment->CommentID . ">" . $comment->Name . "</a>";
	if ($comment->Company != "") { echo ", " . $comment->Company; }
	echo "<br><hr class='shortline'><p class=clear>" . $comment->Text . "</p></div>\n";
}
echo "</div>\n";

if (canComment($id, $uid) || $sess->isAdmin()) {
	if ($sess->isLoggedIn()) {
		echo "<input id='cmtButton' type='button' value='Comment...' onclick='showCommentForm(" . $id . ", " . $sess->getUserID() . ")'><div id='commentFormArea'></div>";

	} else
		echo "<input id='cmtButton' type='button' value='Comment...' onclick='showCommentForm(" . -1 . ", " . -1 . ")'><div id='commentFormArea'></div>";
}


?>

<script src="js/js.js" type="text/javascript"></script>
<script src="js/userfollowing.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js" type="text/JavaScript"></script>
<script type="text/JavaScript">

	function showCommentForm(ideaid, userid) {
		if (userid != -1) {
			$('#commentFormArea').append(
				'<form method="post" action="text/javascript">' +
				'<textarea id="commentText" rows="5" cols="42"></textarea>' +
				'<br><input type="button" value="Send!" onclick="sendComment(' + ideaid + ', ' + userid + ')">' +
				'<input type="button" value="Cancel" onclick=hideCommentForm()>' +
				'</form>').hide().slideDown(1000).fadeIn(1000);

			$('#cmtButton').slideUp(1000).fadeOut(1000);
		} else {
			$('#commentFormArea').append(
				'<div style="padding:1em">Only registered people are allowed to comment. Log in or '+
				'<a href="register.php">register.</a></div>').hide().slideDown(1000).fadeIn(1000);

			$('#cmtButton').slideUp(1000).fadeOut(1000);
		}
	}

	function hideCommentForm() {
		$('#commentFormArea').slideUp(1000).fadeOut(1000, function() {
			// After both animations have ended (=1000ms has passed), empty formarea contents so that
			// when pressing "Comment..." again the form isn't displayed twice.
			$('#commentFormArea').empty();
		});
		$('#cmtButton').slideDown(1000).fadeIn(1000);
	}

	function sendComment(ideaid, userid) {
		var call = 'sendComment';
		var text = document.getElementById('commentText').value;

		$.ajax({
			url: 'ajaxCalls.php',
			type: 'POST',
			data: 'call=' + call + '&ideaid=' + ideaid + '&userid=' + userid + '&comment=' + text,
			success: function(result) {
				var comment = JSON.parse(result);
				var string = "<div id='comment" + comment.Rand + "' class='comment' style='display:none'>" + comment.Date +
					"<a href='showUser.php?id=" + userid + "'> " + comment.Name + "</a>";

				// This whole thing starting from "var string =.." is really only needed because of the "," here. Really.
				if (comment.Company != "") {
					string += ", " + comment.Company;
				}

				string += "<br><hr class='shortline'><p class=clear>" + escapeHTML(text )+ "</p></div>";

				$('#commentsArea').append(string);
				$('#comment'+comment.Rand).hide().slideDown(1000).fadeIn(1000);
			}
		});

		document.getElementById('commentText').value = '';
		hideCommentForm();
	}

	function userFollowIdea(ideaid, userid) {
		var call = 'userFollowIdea';

		$.ajax( {
			url: 'ajaxCalls.php',
			type: 'POST',
			data: 'call=' + call + '&ideaid=' + ideaid + '&userid=' + userid,

			success: function(response) {
				$('#followIdeaButton').empty().fadeOut(500, function() {
					$('#followIdeaButton').append("You are now following this idea.").css('background-color', '#66FF66').fadeIn(1000);

					// This is necessary to get interactivity immediately after starting to follow idea.
					$('#followIdeaButton').hover(function() {
						stopFollowingIdeaEff(ideaid, userid);
					});
				});
			}
		});
	}

	// This function has highlights and whatnot, button effects and interactivity for stopping idea following.
	function stopFollowingIdeaEff(ideaid, userid) {
		$('#followIdeaButton').text("Stop following this idea?").css('background-color', '#FF4D4D').fadeIn(1000);

		$('#followIdeaButton').mouseout(function() {
			$('#followIdeaButton').text("You are following this idea.").css('background-color', '#66FF66');
		});

		$('#followIdeaButton').click(function() {
			 stopFollowingIdea(ideaid, userid);
		});
	}

	// This function does the actual removal for following idea.
	function stopFollowingIdea(ideaid, userid) {
		var call = 'stopFollowingIdea';

		$.ajax({
			url: 'ajaxCalls.php',
			type: 'POST',
			data: 'call=' + call + '&ideaid=' + ideaid + '&userid=' + userid,

			// Reload page.
			success: function(response) {
				window.location = 'showIdea.php?id='+ideaid;
			}
		});
	}
</script>
</body>
</html>
