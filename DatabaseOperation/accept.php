<?php

require_once("details.php");

error_reporting(E_ALL);

function getUnaccepted() {

$mysqli = db_connect();

$sth = $mysqli->prepare("select IdeaID, Name, Description from Idea where Status = 'new';");

$sth->execute();

$sth->bind_result($id, $name, $desc);

while ($sth->fetch()) {
   echo "<div class='accidea'>";
   echo "<input type='checkbox' name='$id' value='false'  />";
   echo "$name";
   echo "$desc</div>";
}

$mysqli->close();

return $sth;

}

// How many new ideas?
function countNewIdeas() {

	$db = db_connect();

	$st = $db->prepare("select Name from Idea where Status = 'new'");
	if (!$st)
		die($db->error);

	if (!$st->execute())
		die($db->error);

	$st->store_result();

	$num = $st->num_rows;

	$db->close();

	return $num;
}

?>
