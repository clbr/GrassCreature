<?php

require_once("session.php");
require_once("DatabaseOperation/accept.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Ideabank - Accept Ideas</title>

	<link href="css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
</head>

<body>

<form method="post" action="accept.php">

<?php
if(isset($_POST['Accept'])){
	acceptSelected();
} else if(isset($_POST['Delete'])){
	deleteSelected();
}

echo "<p>";

getUnaccepted();
?>

</form>

<script type="text/javascript">

function accept_selall() {
	div = document.getElementById('accidea');
	boxes = div.getElementsByTagName('input');
	len = boxes.length;
	but = document.getElementById('acc_toggle');

	state = true;
	if (but.value == 'Select all') {
		but.value = 'Unselect all';
	} else {
		but.value = 'Select all';
		state = false;
	}

	for (i = 0; i < len; i++) {
		if (boxes[i].type != 'checkbox') continue;
		boxes[i].checked = state;
	}
}

</script>

</body>
</html>
