<?php

function addIdea($name, $desc) {

$mysqli = new mysqli("mysql.labranet.jamk.fi", "ideapankki", "0jWF)(p35j%J", "ideapankki_dev");
if (mysqli_connect_errno()) {
 printf("Connect failed: %s\n", mysqli_connect_error());
 exit();
}

$name=(string)$name;
$desc=(string)$desc;

$sth = $mysqli->prepare("INSERT INTO Idea (Name, Description, Version, Status, RequestDate, Inventor) VALUES (?, ?, 0, 'new', CURDATE(), 1)");
$sth->bind_param("ss", $name, $desc);
$sth->execute();


$mysqli->close();
return 0;

}

?>