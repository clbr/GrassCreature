<?php

require_once("details.php");
error_reporting(E_ALL);

function getGroups($userid, $isadmin) {

	$db = db_connect();

	if ($isadmin) {

		$st = $db->query("select Name, Description, GroupID from UserGroup") or die($db->error);

		if ($st->num_rows < 1)
			return;

		echo "<table border=0 class='highlight center'>\n";
		echo "<tr><th>Name</th><th>Description</th><th>Members</th></tr>\n";

		while ($row = $st->fetch_row()) {
			echo "<tr><td>$row[0]</td><td>$row[1]</td><td>";

			$mem = $db->query("select count(User_UserID) from User_has_Group where Group_GroupID = $row[2]") or die ($db->error);
			if ($row = $mem->fetch_row()) {
				echo "$row[0]";
			}
			echo "</td></tr>\n";
		}

		echo "</table>\n";

	} else {

	}


	$db->close();
}



?>
