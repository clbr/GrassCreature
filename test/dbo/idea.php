<?php

require_once("../../DatabaseOperation/details.php");

function addIdea($name, $desc) {

$mysqli = db_connect();

$name=(string)$name;
$desc=(string)$desc;

$sth = $mysqli->prepare("INSERT INTO Idea (Name, Description, Version, Status, RequestDate, Inventor) VALUES (?, ?, 0, 'new', CURDATE(), 1)");
$sth->bind_param("ss", $name, $desc);
$sth->execute();


$mysqli->close();
return 0;

}

?>
