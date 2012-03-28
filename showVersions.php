<?php
require_once("session.php");

if (!isset($_GET["id"])) {
	echo "<script type=\"text/javascript\">" .
		"alert(\"No idea id given\"); window.history.back();" .
		"</script>";
	return;
}

$ideaID = $_GET["id"];

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

/* Get old versions of the idea*/

if ($sess->isAdmin()) {
	require_once("DatabaseOperation/idea.php");
	$versions = getVersions($ideaID);
	echo "<pre>"; var_dump($versions); echo "</pre><br><br>";

	for ($i = 0; $i < count($versions); $i++) {
		echo "<div id=idea" . $versions[$i]->Version . " class=ideaboxtrans>\n" .			
			"<input type=hidden name=id value=hiddenplaceholder>\n" .
			"<table border=0 class=highlight>\n" .
			"\t<tr><td>Version</td><td>" . $versions[$i]->Version . "</td></tr>\n" .
			"\t<tr><td>Name</td><td>" . $versions[$i]->Name . "</td></tr>\n" .
			"\t<tr><td>Description</td><td>" . $versions[$i]->Description . "</td></tr>\n" .
			"\t<tr><td id=idealeft>Status</td><td>" . $versions[$i]->Status . "</td></tr>\n";

		if (isset($versions[$i]->Cost)) echo "\t<tr><td>Cost</td><td>" . $versions[$i]->Cost . "</td></tr>\n";
		if (isset($versions[$i]->AdditionalInfo)) echo "\t<tr><td>Additional info</td><td>" . $versions[$i]->AdditionalInfo . "</td></tr>\n";
		if (isset($versions[$i]->BasedOn)) echo "\t<tr><td>Based on</td><td><a href=\"showIdea.php?id=" . $versions[$i]->BasedOn . "\">" . $versions[$i]->BasedOn . "</a></td></tr>\n";
		if (isset($versions[$i]->RequestDate)) echo "\t<tr><td>Requested date</td><td>" . $versions[$i]->RequestDate . "</td></tr>\n" .

			"\t<tr><td>Accepted date</td><td>" . $versions[$i]->AcceptedDate . "</td></tr>\n" .
			"\t<tr><td>Added date</td><td>" . $versions[$i]->AddingDate . "</td></tr>\n" .
			"\t<tr><td class=bottom>Inventor</td><td class=bottom><a href=\"showUser.php?id=" . $versions[$i]->Inventor . "\">username tähän</a>\t";


		echo "</td></tr>\n" .

	//			"\t<tr><td></td><td></td></tr>\n" .

			"</table></div>\n";
	}
}





?>

<script src="js/js.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js" type="text/JavaScript"></script>
<script type="text/JavaScript"></script>
</body>
</html>
