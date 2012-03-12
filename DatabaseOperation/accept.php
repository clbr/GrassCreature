<?php

require_once("details.php");

function getUnaccepted() {

$mysqli = db_connect();

$sth = $mysqli->prepare("select * from Idea where Status = 'new';");

$sth->execute();

    $result = $sth->get_result();

while ($ideas = $result->fetch_array(MYSQLI_ASSOC)) {
    $name = $ideas["Name"];
    echo $name;
}



$mysqli->close();

return 0;

}

?>
