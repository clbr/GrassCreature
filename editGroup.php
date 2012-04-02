<?php require_once("session.php");

$sess->mustBeLoggedIn();

if (!isset($_GET["id"])) {
	echo "<script type=\"text/javascript\">" .
		"alert(\"No id given\"); window.history.back();" .
		"</script>";
	return;
}

$id = $_GET["id"];


require_once("DatabaseOperation/groups.php");


if (isset($_POST["chk"]) && isset($_POST["remove"])) {

	foreach($_POST["chk"] as $val)
		deleteFromGroup($val, $id);

} else if (isset($_POST["inv"]) && isset($_POST["uname"])) {

	addToGroup($_POST["uname"], $id);
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Ideabank</title>

	<link
	<?php
		require_once("getTheme.php");
		getTheme($sess->getUserID());
	?>
 rel="stylesheet" type="text/css">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<base target=main>
</head>

<body class=lining>

<h2>Editing members of group
<?php

echo getGroupName($id);

?>
</h2>

<?php
echo "<form id=editgroup action=editGroup.php?id=$id method=post>";
?>

<input type=button name=invite value="Invite new user" onclick='inviteusr()'>
<input type=submit name=remove value="Remove selected users">
<br><br>
<div id=edgroups></div>
<br><br>

<?php

listGroupMembers($id);

?>


</form>

<script src="js/js.js" type="text/javascript"></script>
<script type="text/javascript">

function inviteusr() {

	var div = document.getElementById('edgroups');
	div.innerHTML = '<select name=uname id=uname></select> <input type=submit name=inv value=Send>';

	var dest = document.getElementById('uname');
	dest.focus();

	lstusers_ajaxy();
}

function lstusers_ajaxy() {

	var x = new XMLHttpRequest();

	var dest = document.getElementById('uname');

	x.open("POST", "ajaxCalls.php", true);

	x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	x.setRequestHeader("Content-length", 11);
	x.setRequestHeader("Connection", "close");

	x.onreadystatechange = function() {
		if (x.readyState == 4) {
			var str = x.responseText.split('\n');
			var len = str.length;

			for (var i = 0; i < len; i++) if(str[i].length > 1)
				dest.options[dest.options.length] = new Option(str[i], str[i]);
		}
	}

	x.send('call=users');
}


</script>
</body>
</html>
