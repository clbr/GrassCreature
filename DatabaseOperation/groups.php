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

		$st = $db->prepare("select Name, Description, GroupID from UserGroup inner join User_has_Group on Group_GroupID = GroupID where User_UserID = ?") or die($db->error);

		$st->bind_param("i", $userid);
		$st->execute();
		$st->bind_result($name, $desc, $gid);
		$st->store_result();

		if ($st->num_rows < 1)
			return;

		echo "<table border=0 class='highlight center'>\n";
		echo "<tr><th></th><th>Name</th><th>Description</th><th>Members</th></tr>\n";

		while ($st->fetch()) {
			echo "<tr><td>";
			echo "<input type=checkbox name='chk[]' value=$gid>";
			echo "</td><td>$name</td><td>$desc</td><td>";

			$mem = $db->query("select count(User_UserID) from User_has_Group where Group_GroupID = $gid") or die ($db->error);
			if ($row = $mem->fetch_row()) {
				echo "$name";
			}
			echo "</td></tr>\n";
		}

		echo "</table>\n";
	}


	$db->close();
}


function addGroup($name, $desc, $uid) {

	$db = db_connect();

	$st = $db->prepare("insert into UserGroup (Name, Description) values (?, ?)") or die($db->error);

	$st->bind_param("ss", $name, $desc);
	$st->execute();
	$st->close();

	// Query the id
	$st = $db->prepare("select GroupID from UserGroup where Name = ?") or die($db->error);

	$st->bind_param("s", $name);
	$st->execute();
	$st->bind_result($gid);
	$st->fetch();
	$st->close();

	// Add me as a member
	$st = $db->prepare("insert into User_has_Group (User_UserID, Group_GroupID) values (?, ?)") or die($db->error);

	$st->bind_param("ii", $uid, $gid);
	$st->execute();
	$st->close();

	$db->close();
}


function deleteGroup($id) {

}

?>
