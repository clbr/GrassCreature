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
				echo "$row[0]";
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
	if (!$st->execute()) die($db->error);
	$st->close();

	// Query the id
	$st = $db->prepare("select GroupID from UserGroup where Name = ?") or die($db->error);

	$st->bind_param("s", $name);
	$st->execute();
	$st->bind_result($gid);
	if (!$st->fetch()) return;
	$st->close();

	// Add me as a member
	$st = $db->prepare("insert into User_has_Group (User_UserID, Group_GroupID) values (?, ?)") or die($db->error);

	$st->bind_param("ii", $uid, $gid);
	$st->execute();
	$st->close();

	$db->close();
}


function deleteGroup($id) {

	$db = db_connect();

	$st = $db->prepare("select count(User_UserID) from User_has_Group where Group_GroupID = ?") or die($db->error);
	$st->bind_param("i", $id);
	$st->execute();

	$st->bind_result($num);
	$st->fetch() or die("Fetch error");

	if ($num > 0)
		die("Can't remove group with members");

	$st->close();

	$st = $db->prepare("delete from UserGroup where GroupID = ?") or die($db->error);
	$st->bind_param("i", $id);
	$st->execute();

	$db->close();
}

function getGroupName($id) {

	$db = db_connect();

	$st = $db->prepare("select Name from UserGroup where GroupID = ?") or die($db->error);
	$st->bind_param("i", $id);
	$st->execute();

	$st->bind_result($name);
	$st->fetch() or die("Fetch error");

	$db->close();

	return $name;
}


function listGroupMembers($id) {

	global $sess;

	$db = db_connect();

	$st = $db->prepare("select Name, UserID, User_UserID from User_has_Group inner join User on User_UserID = UserID where Group_GroupID = ?") or die ($db->error);
	$st->bind_param("i", $id);
	$st->execute();
	$st->bind_result($name, $uid1, $uid2);

	$st->store_result();
	if ($st->num_rows < 1)
		return;

	echo "<table border=0 class=\"highlight center\">\n";

	while ($st->fetch()) {

		if ($sess->isAdmin() || $sess->getUserID() == $uid1)
			echo "<tr><td><input type=checkbox name='chk[]' value='$uid1'></td><td><a href='showUser.php?id=$uid1'>$name</a></td></tr>\n";
		else
			echo "<tr><td></td><td><a href='showUser.php?id=$uid1'>$name</a></td></tr>\n";
	}

	echo "</table>\n";

	$db->close();
}

function deleteFromGroup($uid, $gid) {

	global $sess;

	// User can only remove himself from a group
	if (!($sess->isAdmin() || $sess->getUserID() == $uid))
		return;

	$db = db_connect();

	$st = $db->prepare("delete from User_has_Group where User_UserID = ? and Group_GroupID = ?") or die($db->error);
	$st->bind_param("ii", $uid, $gid);
	$st->execute();

	$db->close();
}

function addToGroup($uname, $gid) {

	$db = db_connect();

	// Who am I?
	$st = $db->prepare("select UserID from User where Name = ?") or die($db->error);
	$st->bind_param("s", $uname);
	$st->execute();
	$st->bind_result($uid);
	$st->fetch() or die("Fetch error");
	$st->close();

	// Add me as a member
	$st = $db->prepare("insert into User_has_Group (User_UserID, Group_GroupID) values (?, ?)") or die($db->error);

	$st->bind_param("ii", $uid, $gid);
	$st->execute();
	$st->close();

	$db->close();
}

?>
