<?php

session_start();

class sess {

	function isLoggedIn() {

		if (isset($_SESSION["userID"]))
			return true;
		else
			return false;
	}

	function isAdmin() {

		if (isset($_SESSION["isAdmin"]))
			return true;
		else
			return false;
	}
}

$sess = new sess;

if ($sess->isLoggedIn()) echo "logged in";
else echo "logged out";




?>
