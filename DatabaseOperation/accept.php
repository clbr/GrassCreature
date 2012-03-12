<?php

require_once("details.php");

echo "VITTU TOIMI";
error_reporting(E_ALL);

function getUnaccepted() {

$mysqli = db_connect();

$sth = $mysqli->prepare("select * from Idea where Status = 'new';");

$sth->execute();

$result = $sth->get_result();

echo "VITTU TOIMI";

while ($ideas = $result->fetch_array(MYSQLI_ASSOC)) {
    $name = $ideas["Name"];
    echo $name;
}

echo "VITTU TOIMI2";

$mysqli->close();

return 0;

}

getUnaccepted();
echo "VITTU TOIMI3";

?>
