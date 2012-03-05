<?php

function db_connect() {

$mysqli = new mysqli("mysql.labranet.jamk.fi", "ideapankki", "0jWF)(p35j%J", "ideapankki_dev");
if (mysqli_connect_errno())
	die(mysqli_connect_error());

return $mysqli;

}

?>
