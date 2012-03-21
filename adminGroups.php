<?php require_once("session.php");

$sess->mustBeLoggedIn();

require_once("DatabaseOperation/groups.php");

if (isset($_POST["name"])) {

	$desc = "";
	if (isset($_POST["desc"]))
		$desc = $_POST["desc"];

	addGroup($_POST["name"], $desc, $sess->getUserID());

} else if (isset($_POST["chk"])) {

	foreach ($_POST["chk"] as $chval) {
		if (isset($chval))
			deleteGroup($chval);
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



<h2>
<?php
if ($sess->isAdmin())
	echo "Manage all groups";
else
	echo "Manage your groups";
?>
</h2>

<form action=adminGroups.php method=post class=indent>

<input type=button name=add value="Create new group" onclick='groupadd()''>
<input type=button name=remove value="Delete group" onclick='groupdel()'>
<input type=button name=members value="Edit group members" onclick='groupedit()'>

<p>
<div id=groupsdiv class="center" style="display:inline-block;">

</div>
<br>

<?php

getGroups($sess->getUserID(), $sess->isAdmin());

?>

</form>

<script type="text/javascript">

var gdiv = document.getElementById('groupsdiv');

function groupadd() {
	gdiv.innerHTML = "<table border=0>" +
				"<tr><td>Name:</td><td><input type=text name=name size=20></td></tr>" +
				"<tr><td>Description:</td><td><input type=text name=desc size=20></td></tr>" +
				"</table>" +
				"<input type=submit value=Send>";
}

function groupdel() {

	var boxes = document.getElementsByName('chk[]');
	var len = boxes.length;

	for (i = 0; i < len; i++) {
		if (boxes[i].checked == true)
			break;
	}

	if (i == len) {
		alert("Please select at least one group");
		return;
	}

	boxes[0].form.submit();
}

function groupedit() {

	var boxes = document.getElementsByName('chk[]');
	var len = boxes.length;

	for (i = 0; i < len; i++) {
		if (boxes[i].checked == true)
			break;
	}

	if (i == len) {
		alert("Please select a group");
		return;
	}

	window.location = 'editGroup.php?id=' + boxes[i].value;
}

</script>
<script src="js/js.js" type="text/javascript"></script>
</body>
</html>
