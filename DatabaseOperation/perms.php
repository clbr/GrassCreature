<?php

function getIdeaPermissions($id) {

	$db = db_connect();

	$st = $db->prepare("select Name, CanComment, CanView, CanEdit, Group_GroupID from Idea_has_Group inner join UserGroup on GroupID = Group_GroupID where Idea_IdeaID = ?") or die($db->error);
	$st->bind_param("i", $id);
	$st->execute();
	$st->bind_result($name, $comment, $view, $edit, $gid);

	$st->store_result();
	if ($st->num_rows < 1)
		return;

	echo "<input type=hidden name=totalgroups value=$st->num_rows>\n";

	echo "<table border=0 class='highlight center'>\n";
	echo "<tr><th>Group</th><th>Can comment</th><th>Can view</th><th>Can edit</th></tr>\n";

	while ($st->fetch()) {

		echo "<input type=hidden name='groups[]' value=$gid>";

		if ($gid == 0) { // We hijack the admin group for 'everyone' as admins can do everything
			echo "<tr><td>Everyone</td>";
		} else {
			echo "<tr><td>$name</td>";
		}

		echo "<td><input type=checkbox name='comment$gid' ";

		if ($comment) echo "checked";
		echo "></td><td><input type=checkbox name='view$gid' ";

		if ($view) echo "checked";
		echo "></td><td><input type=checkbox name='edit$gid' ";

		if ($edit) echo "checked";
		echo "></td></tr>\n";
	}

	echo "</table>\n";

	$db->close();

	return $name;
}

function addPermGroup($grp, $id) {

	$db = db_connect();

	$st = $db->prepare("select GroupID from UserGroup where Name = ?") or die($db->error);
	$st->bind_param("s", $grp);
	$st->execute();
	$st->bind_result($gid);

	$st->fetch() or die("Fetch error");
	$st->close();


	$st = $db->prepare("select Idea_IdeaID from Idea_has_Group where Idea_IdeaID = ? and Group_GroupID = ?") or die($db->error);
	$st->bind_param("ii", $id, $gid);
	$st->execute();

	$st->store_result();
	if ($st->num_rows > 0)
		return;

	$st->close();

	$st = $db->prepare("insert into Idea_has_Group (Idea_IdeaID, Group_GroupID, CanComment, CanView, CanEdit) values (?, ?, false, false, false)") or die($db->error);
	$st->bind_param("ii", $id, $gid);
	$st->execute();

	$db->close();
}

function setPerms($id, $gid, $comment, $view, $edit) {

	$db = db_connect();

	$st = $db->prepare("update Idea_has_Group set CanComment=?, CanView=?, CanEdit=? where Idea_IdeaID=? and Group_GroupID = ?") or die($db->error);
	$st->bind_param("iiiii", $comment, $view, $edit, $id, $gid);
	$st->execute();

	$db->close();
}

function canComment($id, $uid) {

	$db = db_connect();

	// inventor can always do anything
	$inv = getIdeaInventor($id);
	if ($inv == $uid) return true;

	// Check if everyone has the right to comment on this idea
	$st = $db->prepare("select CanComment from Idea_has_Group where Idea_IdeaID = ? and Group_GroupID = 0") or die($db->error);
	$st->bind_param('i', $id);
	$st->execute();
	$st->bind_result($view);
	if (!$st->fetch())
		return false;

	$st->close();

	// Check if you have the right to comment on this idea
	if (!$view) {
		$st = $db->prepare("select CanComment from Idea_has_Group inner join User_has_Group on User_has_Group.Group_GroupID = Idea_has_Group.Group_GroupID where Idea_IdeaID = ? and User_UserID = ?") or die($db->error);
		$st->bind_param('ii', $id, $uid);
		$st->execute();
		$st->bind_result($view);
		while ($st->fetch()) {
			if ($view) return true;
		}

		return false;

		$st->close();

	}

	return true;
}

function canEdit($id, $uid) {

	$db = db_connect();

	// inventor can always do anything
	$inv = getIdeaInventor($id);
	if ($inv == $uid) return true;

	// Check if everyone has the right to edit this idea
	$st = $db->prepare("select CanEdit from Idea_has_Group where Idea_IdeaID = ? and Group_GroupID = 0") or die($db->error);
	$st->bind_param('i', $id);
	$st->execute();
	$st->bind_result($view);
	if (!$st->fetch())
		return false;

	$st->close();

	// Check if you have the right to edit this idea
	if (!$view) {
		$st = $db->prepare("select CanEdit from Idea_has_Group inner join User_has_Group on User_has_Group.Group_GroupID = Idea_has_Group.Group_GroupID where Idea_IdeaID = ? and User_UserID = ?") or die($db->error);
		$st->bind_param('ii', $id, $uid);
		$st->execute();
		$st->bind_result($view);
		while ($st->fetch()) {
			if ($view) return true;
		}

		return false;

		$st->close();

	}

	return true;
}

function canView($id, $uid) {

	$db = db_connect();

	// inventor can always do anything
	$inv = getIdeaInventor($id);
	if ($inv == $uid) return true;

	// Check if everyone has the right to view this idea
	$st = $db->prepare("select CanView from Idea_has_Group where Idea_IdeaID = ? and Group_GroupID = 0") or die($db->error);
	$st->bind_param('i', $id);
	$st->execute();
	$st->bind_result($view);
	if (!$st->fetch())
		return false;

	$st->close();

	// Check if you have the right to view this idea
	if (!$view) {
		$st = $db->prepare("select CanView from Idea_has_Group inner join User_has_Group on User_has_Group.Group_GroupID = Idea_has_Group.Group_GroupID where Idea_IdeaID = ? and User_UserID = ?") or die($db->error);
		$st->bind_param('ii', $id, $uid);
		$st->execute();
		$st->bind_result($view);
		while ($st->fetch()) {
			if ($view) return true;
		}

		return false;

		$st->close();

	}

	return true;
}



?>
