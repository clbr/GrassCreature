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
   echo "<input type='checkbox' name='chkbox[]' value='$id'  />";
   echo "$name";
   echo "$desc</div>";
}

$mysqli->close();

return $sth;

}

function acceptSelected() {
if(isset($_POST['chkbox']))
{
	foreach($_POST['chkbox'] as $chkval) {
		if(isset($chkval)) {

$mysqli = db_connect();

$sth = $mysqli->prepare("update Idea set Status='active' where IdeaID=?;");

$sth->bind_param("s", $chkval);

$sth->execute();

$mysqli->close();

}

	}
}
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
