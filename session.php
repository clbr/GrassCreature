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

	function printBox() {

		echo "<div id=loginbox>\n";

		echo "\t";
		if ($this->isLoggedIn()) echo "logged in";
		else echo "logged out";

		echo "\n</div>";
	}
}

$sess = new sess;


?>
