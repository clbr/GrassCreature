<?php require_once("session.php");

$sess->mustBeLoggedIn();

if (!isset($_GET["id"])) {
	echo "<script type=\"text/javascript\">" .
		"alert(\"No idea id given\"); window.history.back();" .
		"</script>";
	return;
}

$id = $_GET["id"];

require_once("DatabaseOperation/idea.php");

if (!$sess->isAdmin() && getIdeaInventor($id) != $sess->getUserID()) {
	echo "<script type=\"text/javascript\">" .
		"alert(\"You don't have rights to edit the permissions of this idea.\"); window.history.back();" .
		"</script>";
	return;
}

// Permissions checked. Post checks

if (isset($_POST["add"]) && isset($_POST["group"])) {

	addPermGroup($_POST["group"], $id);

} else if (isset($_POST["save"])) {

	$len = $_POST["totalgroups"];

	for ($i = 0; $i < $len; $i++) {
		$gid = $_POST["groups"][$i];
		$comment = 0;
		$view = 0;
		$edit = 0;

		if (isset($_POST["comment" . $gid])) $comment = 1;
		if (isset($_POST["edit" . $gid])) $edit = 1;
		if (isset($_POST["view" . $gid])) $view = 1;

		setPerms($id, $gid, $comment, $view, $edit);
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

<h2>Edit permissions for
<?php
echo getIdeaName($id);
?>
</h2>

<?php
echo "<form id=permform method=post action='perms.php?id=$id'>";
?>

<input type=button name=addgroup value="Add group to permission list" onclick='addgrp()'>
<input type=hidden name=group>
<input type=submit name=save value="Save">
<br><br>
<div id=addgrpdiv></div>
<br><br>

<?php
getIdeaPermissions($id);
?>

<br><br>
<input type=submit name=save value="Save">

</form>

<script src="js/js.js" type="text/javascript"></script>
<script type="text/javascript">

function addgrp() {

	var div = document.getElementById('addgrpdiv');
	div.innerHTML = '<input type=text size=15 name=group id=grptext> <input type=submit name=add value=Add>' +
			'<br><select onchange="pickgrp()" id=selectgroups></select>';

	document.getElementById('grptext').focus();

	perms_ajaxy();
}

function pickgrp() {

	var t = document.getElementById('grptext');
	var s = document.getElementById('selectgroups');
	t.value = s.options[s.selectedIndex].text;
}

function perms_ajaxy() {

	var x = new XMLHttpRequest();

	var dest = document.getElementById('selectgroups');

	x.open("POST", "ajaxCalls.php", true);

	x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	x.setRequestHeader("Content-length", 11);
	x.setRequestHeader("Connection", "close");

	x.onreadystatechange = function() {
		if (x.readyState == 4) {
			var str = x.responseText.split('\n');
			var len = str.length;

			for (var i = 0; i < len; i++) if(str[i].length > 1)
				dest.options[dest.options.length] = new Option(str[i]);
		}
	}

	x.send('call=groups');

	dest.innerHTML = "<img src=\"img/loading.gif\" width=16 height=16>";
}

</script>
</body>
</html>
