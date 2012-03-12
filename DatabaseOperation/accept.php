<?php

require_once("details.php");

error_reporting(E_ALL);

function getUnaccepted() {

$mysqli = db_connect();

$sth = $mysqli->prepare("select IdeaID, Name, Description from Idea where Status = 'new';");

$sth->execute();

$sth->bind_result($id, $name, $desc);

while ($sth->fetch()) {
   echo "<input type='checkbox' name='$id' value='false'  />";
   printf ("name: %s | desc: %s\n", $name, $desc);
}

$mysqli->close();

return $sth;

}

?>
