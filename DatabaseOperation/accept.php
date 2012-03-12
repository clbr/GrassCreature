<?php

require_once("details.php");

error_reporting(E_ALL);

function getUnaccepted() {

$mysqli = db_connect();

$sth = $mysqli->prepare("select Name, Description from Idea where Status = 'new';");

$sth->execute();

$sth->bind_result($name, $desc);

while ($sth->fetch()) {
   printf ("name: %s | desc: %s\n", $name, $desc);
}

$mysqli->close();

return 0;

}

?>
