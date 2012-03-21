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

		$last = "";

		if (empty($co) && empty($coadd))
			$last = "class=bottom";

		echo "<div id=userdiv class=ideaboxtrans>\n" .
			"<input type=hidden name=id value=$id>\n" .
			"<table border=0 width='100%' class=highlight>\n" .

			"\t<tr><td $last>Name</td><td $last>$name</td></tr>\n";

		$last = "";

		if (empty($coadd))
			$last = "class=bottom";

		if (!empty($co)) echo "\t<tr><td $last>Company</td><td $last>$co</td></tr>\n";
		if (!empty($coadd)) echo "\t<tr><td class=bottom>Additional company info</td><td class=bottom>$coadd</td></tr>\n";

		echo "</table>\n";

	}
	$st->close();

	// Only show ideas to logged in users
	if ($isLoggedIn) {

		$st = $db->prepare("select IdeaID, Name from Idea where Inventor = ?");
		$st->bind_param("i", $id);

		$st->execute();

		$st->bind_result($ideaid, $ideaname);

		$st->store_result();
		$rows = $st->num_rows;

		if ($rows > 0) {

			echo "<br><hr><br>\n";

			echo "<table border=0 width='100%' class=highlight>\n" .
				"<tr><th>Ideas added by $name</th></tr>";

			for ($i = 1; $st->fetch(); $i++) {

				$last = "";
				if ($i == $rows)
					$last = "class=bottom";

				echo "\t<tr><td $last><a href=\"showIdea.php?id=$ideaid\">$ideaname</a></td></tr>\n";
			}

			echo "</table>\n";
		}
	}

	echo "</div>\n";

	$db->close();
}



?>
