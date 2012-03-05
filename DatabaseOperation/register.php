<?php

require_once("details.php");

function register_user($uname, $mail, $pw, $comp = "", $compadd = "") {

	$db = db_connect();

	$st = $db->prepare("insert into User (Name, Email, PwdHash, Company, CompanyAddress) values (?, ?, ?, ?, ?)");
	if (!$st)
		die($db->error);

	$st->bind_param("sssss", $uname, $mail, $pw, $comp, $compadd);

	if (!$st->execute())
		die($db->error);

	$db->close();

}


?>
