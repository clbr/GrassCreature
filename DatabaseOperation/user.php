<?php

//error_reporting(E_ALL);
require_once('details.php');
require_once('session.php');

function getUser($id) {

	$db = db_connect();

	$st = $db->prepare("select Name, Company, CompanyAddress from User where UserID = ?");
	$st->bind_param("i", $id);

	$st->execute();

	$st->bind_result($name, $co, $coadd);
	if ($st->fetch()) {
		echo "$name, $co, $coadd";
	}
	$st->close();

	if ($sess->isLoggedIn()) {

		$st = $db->prepare("select IdeaID, Name from Idea where Inventor = ?");
		$st->bind_param("i", $id);

		$st->execute;

		$st->bind_result($ideaid, $ideaname);

		while ($st->fetch()) {
			echo "$ideaname, $ideaid<br>";
		}
	}

	$db->close();
}



?>
