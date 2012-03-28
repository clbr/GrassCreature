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
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js" type="text/JavaScript"></script>
	<script type="text/JavaScript">

	/* Idea following stuff, show new comments in followed ideas */

	function showFollowedIdeas(userid) {
		var call = 'followedIdeas';

		$.ajax({
			url: 'ajaxCalls.php',
			type: 'POST',
			data: 'call=' + call + '&userid=' + userid,
			success: function(response) {
				var ideas = JSON.parse(response);

				if (ideas.length > 0) {
					var count = 0;
					for (i in ideas)
						count += ideas[i].comments;

					var notify = "Your followed ideas have <b><span style='color:#248F24'>" + count + "</span></b> new comments in " +
					"<b><span style='color:#248F24'>" + ideas.length + "</span></b> ";

					if (ideas.length == 1)
						notify += "idea.";
					else
						notify += "different ideas.";

					document.getElementById("followedIdeas").innerHTML = notify;

					// Div is initially hidden, so set display to block (= a form of show).
					$('#followedIdeas').css('display', 'block').click(function() {
						showFollowedIdeaComments(ideas);
					});
				}
			}
		});
	}

	function showFollowedIdeaComments(ideas) {
		$('#followedIdeas').fadeOut(200, function() {
			$('#followedIdeas').empty().css('cursor', 'auto');

			for (i in ideas) {
				document.getElementById("followedIdeas").innerHTML += "<div>Idea <a href='showIdea.php?id=" + ideas[i].ideaID + "'>" + ideas[i].ideaname +
					"</a> has <b><span style='color:#248F24'>" + ideas[i].comments + "</span></b> new comments.</div>";
			}
			
			$('#followedIdeas').fadeIn(500);
		});
	}

	/* User following stuff, show newly added ideas by followed users */

	function showFollowedUsers(userid) {
		var call = 'followedUsers';

		$.ajax({
			url: 'ajaxCalls.php',
			type: 'POST',
			data: 'call=' + call + '&userid=' + userid,
			success: function(response) {
				var users = JSON.parse(response);

				if (users.length > 0) {
					/*var count = 0;
					for (i in users)
						count += parseInt(users[i].Count); // Count is returned by the DB as a string so...*/

					var notify = "<b><span style='color:#248F24'>" + users.length +
						"</span></b> new ideas by your followed users.";

					/*if (users.length == 1)
						notify += " user.";
					else
						notify += " different users.";*/

					document.getElementById("followedUsers").innerHTML = notify;

					// Div is initially hidden, so set display to block (= a form of show).
					$('#followedUsers').css('display', 'block').click(function() {
						showFollowedUsersIdeas(users);
					});
				}
			}
		});
	}

	function showFollowedUsersIdeas(users) {
		$('#followedUsers').fadeOut(200, function() {
			$('#followedUsers').empty().css('cursor', 'auto');

			for (i in users) {
			//document.getElementById("followedUsers").innerHTML += 
			$("#followedUsers").append("User <a href='showUser.php?id=" + users[i].UserID + "'>" + users[i].Username +
			"</a> has added the idea <a href='showIdea.php?id=" + users[i].IdeaID + "'>\"" + users[i].Ideaname + "\"</a>.<br>");
			}

			$('#followedUsers').fadeIn(500);
		});
	}
</script>
</head>

<body class=lining>


<h1 id=frontwelcome>Welcome to the Ideabank!</h1>


<?php


require_once("DatabaseOperation/accept.php");
require_once("Top10.php");

if ($sess->isAdmin()) {

	$num = countNewIdeas();

	if ($num > 0) {
		echo "<div id=adminnote class=ideabox>\n" .
			"<a href=\"accept.php\">" .
			"You have $num new ideas to accept.</a><br>" .
			"</div><p>\n";
	}

}

/* Followed idea/users stuffs here. */

if ($sess->isLoggedIn()) {
	echo '<script type="text/JavaScript">showFollowedUsers(' . $sess->getUserID() . ');</script>';
	echo "<div id=followedUsers></div><br><br><br>";
	echo '<script type="text/JavaScript">showFollowedIdeas(' . $sess->getUserID() . ');</script>';
	echo "<div id=followedIdeas></div><br>";
}

?>

<div id=newestideas class=ideabox>
<h3>Top 10 newest ideas</h3>
<?php
top10_newest();
?>
</div>

<div id=bestideas class=ideabox>
<h3>Top 10 best ideas</h3>
<?php
top10_rated();
?>
</div>

<div id=mostcommentedideas class=ideabox>
<h3>Top 10 most commented ideas</h3>
<?php
top10_commented();
?>
</div>

<div id=lastcommentedideas class=ideabox>
<h3>Top 10 newest comments</h3>
<?php
top10_latest_comments();
?>
</div>


<script src="js/js.js" type="text/javascript"></script>

</body>
</html>
