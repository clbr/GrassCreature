<?php

require_once("details.php");

error_reporting(E_ALL);

function getUnaccepted() {

$mysqli = db_connect();

$sth = $mysqli->prepare("select * from Idea where Status = 'new';");

$sth->execute();

$sth->bind_result($name);

while ($sth->fetch()) {
   printf ("%s\n", $name);
}

$mysqli->close();

return 0;

}

?>
