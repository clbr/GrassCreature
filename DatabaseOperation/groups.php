<?php

require_once("details.php");
error_reporting(E_ALL);

function getGroups($userid, $isadmin) {

	$db = db_connect();

	if ($isadmin) {

		$st = $db->query("select Name, Description from UserGroup") or die($db->error);

		if ($st->num_rows < 1)
			return;

		echo "<table border=1 class=highlight_tbl>\n";
		echo "<tr><th>Name</th><th>Description</th><th>Members</th></tr>\n";

		while ($row = $st->fetch_row()) {
			echo "<tr><td>$row[0]</td><td>$row[1]</td><td></td></tr>\n";
		}

		echo "</table>\n";

	} else {

		$st = $db->query("select Name, Description from UserGroup") or die($db->error);
	}


	$db->close();
}



?>
