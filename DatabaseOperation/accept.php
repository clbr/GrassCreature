<?php

require_once("details.php");

error_reporting(E_ALL);

function getUnaccepted() {

$mysqli = db_connect();

$sth = $mysqli->prepare("select * from Idea where Status = 'new';");

$sth->execute();

$result = $sth->store_result();

while ($ideas = $result->fetch_array(MYSQLI_ASSOC)) {
    $name = $ideas["Name"];
    echo $name;
}

$mysqli->close();

return 0;

}

?>
