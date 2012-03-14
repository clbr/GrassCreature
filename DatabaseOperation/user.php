<?php

error_reporting(E_ALL);
require_once('details.php');

function getUser($id, $isLoggedIn) {

	$db = db_connect();

	$st = $db->prepare("select Name, Company, CompanyAddress from User where UserID = ?");
	$st->bind_param("i", $id);

	$st->execute();

	$st->bind_result($name, $co, $coadd);
	if ($st->fetch()) {

		echo "<div id=userdiv>\n" .
			"<input type=hidden name=id value=$id>\n" .
			"<table border=0>\n" .

			"\t<tr><td>Name</td><td>$name</td></tr>\n";

		if (!empty($co)) echo "\t<tr><td>Company</td><td>$co</td></tr>\n";
		if (!empty($coadd)) echo "\t<tr><td>Additional company info</td><td>$coadd</td></tr>\n";

		echo "</table>\n";

	}
	$st->close();

	// Only show ideas to logged in users
	if ($isLoggedIn) {

		echo "<br><br>\n";

		$st = $db->prepare("select IdeaID, Name from Idea where Inventor = ?");
		$st->bind_param("i", $id);

		$st->execute();

		$st->bind_result($ideaid, $ideaname);

		echo "<table border=0>\n" .
			"<tr><th>Ideas added by $name</th></tr>";

		while ($st->fetch()) {

			echo "\t<tr><td><a href=\"showIdea.php?id=$ideaid\">$ideaname</a></td></tr>\n";

		}

		echo "</table>\n";
	}

	echo "</div>\n";

	$db->close();
}



?>
