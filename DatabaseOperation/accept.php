<?php

require_once("details.php");

error_reporting(E_ALL);

function getUnaccepted() {

$mysqli = db_connect();

$sth = $mysqli->prepare("select IdeaID, Name, Description from Idea where Status = 'new';");

$sth->execute();

$sth->bind_result($id, $name, $desc);

echo "<div class='accidea' id='accidea'>";

echo "<input type=button value='Select all' onclick='accept_selall()' id='acc_toggle'><hr>";

while ($sth->fetch()) {
   echo "<input type='checkbox' name='chkbox[]' value='$id'  />";
   echo "<a href='showIdea.php?id=$id'>$name, " .
	"$desc</a><br>\n";
}

echo '<hr><div class=center><input type="submit" name="Accept" value="Accept">' .
	'<input type="submit" name="Delete" value="Delete"></div>';
echo "</div>\n";

$mysqli->close();

return $sth;

}

function acceptIdea($id) {

	$mysqli = db_connect();

	$sth = $mysqli->prepare("update Idea set Status='active' where IdeaID=?;");

	$sth->bind_param("i", $id);
	$sth->execute();

	$mysqli->close();
}

function acceptSelected() {
$amount=0;
if(isset($_POST['chkbox']))
{
	foreach($_POST['chkbox'] as $chkval) {
		if(isset($chkval)) {
			$amount++;

			acceptIdea($chkval);

		}
	}

	echo $amount . " ideas accepted";
}
}

function deleteSelected() {

$amount=0;
if(isset($_POST['chkbox']))
{
        foreach($_POST['chkbox'] as $chkval) {
                if(isset($chkval)) {
$amount++;

$mysqli = db_connect();

$sth = $mysqli->prepare("DELETE FROM Idea WHERE IdeaID = ?;");

$sth->bind_param("s", $chkval);

$sth->execute();

$mysqli->close();

}

        }
echo $amount . " ideas deleted";
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
