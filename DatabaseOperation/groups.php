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
		echo "<tr><th></th><th>Name</th><th>Description</th><th>Members</th></tr>\n";

		while ($row = $st->fetch_row()) {
			echo "<tr><td>";
			echo "<input type=checkbox name='chk[]' value=$row[2]>";
			echo "</td><td>$row[0]</td><td>$row[1]</td><td>";

			$mem = $db->query("select count(User_UserID) from User_has_Group where Group_GroupID = $row[2]") or die ($db->error);
			if ($row = $mem->fetch_row()) {
				echo "$row[0]";
			}
			echo "</td></tr>\n";
		}

		echo "</table>\n";

	} else {

		$st = $db->prepare("select Name, Description, GroupID from UserGroup, User_has_Group where User_UserID = ? and Group_GroupID = GroupID") or die($db->error);

		$st->bind_param("i", $userid);
		$st->execute();
		$st->bind_result($name, $desc, $gid);
		$st->store_result();

		if ($st->num_rows < 1)
			return;

		echo "<table border=0 class='highlight center'>\n";
		echo "<tr><th></th><th>Name</th><th>Description</th><th>Members</th></tr>\n";

		while ($row = $st->fetch_row()) {
			echo "<tr><td>";
			echo "<input type=checkbox name='chk[]' value=$row[2]>";
			echo "</td><td>$row[0]</td><td>$row[1]</td><td>";

			$mem = $db->query("select count(User_UserID) from User_has_Group where Group_GroupID = $row[2]") or die ($db->error);
			if ($row = $mem->fetch_row()) {
				echo "$row[0]";
			}
			echo "</td></tr>\n";
		}

		echo "</table>\n";
	}


	$db->close();
}



?>
