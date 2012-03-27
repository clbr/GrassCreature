<?php

require_once("details.php");

function register_user($uname, $mail, $pw, $comp = "", $compadd = "") {

	$db = db_connect();

	$uname = htmlspecialchars($uname);
	$mail = htmlspecialchars($mail);
	$comp = htmlspecialchars($comp);
	$compadd = htmlspecialchars($compadd);

	$st = $db->prepare("insert into User (Name, Email, PwdHash, Company, CompanyAddress) values (?, ?, ?, ?, ?)");
	if (!$st)
		die($db->error);

	$st->bind_param("sssss", $uname, $mail, $pw, $comp, $compadd);

	if (!$st->execute())
		die($db->error);

	$db->close();

}

// AJAX username check
if (isset($_GET["check"])) {

	$u = $_GET["check"];

	$db = db_connect();

	$st = $db->prepare("select Name from User where Name=?");
	if (!$st)
		die($db->error);

	$st->bind_param("s", $u);

	if (!$st->execute())
		die($db->error);

	$st->store_result();

	if ($st->num_rows > 0) {
		echo "<img src=\"img/fail.png\" width=24 height=24 title=\"This username is already taken.\">";
	} else {
		echo "<img src=\"img/success.png\" width=24 height=24 title=\"This username is free.\">";
	}

	$db->close();
}

?>
