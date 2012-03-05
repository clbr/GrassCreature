<?php

require_once("details.php");

function addIdea($name, $desc) {

$mysqli = db_connect();

$sql = "INSERT INTO Idea (Name, Description, Version, Status, RequestDate, Inventor) VALUES ('$name', '$desc', 0, 'new', CURDATE(), 1)";
$result = $mysqli->query($sql);
if(!$result) {
$mysqli->close();
return 1; }

$mysqli->close();
return 0;

}

?>
