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
		if ($this->isLoggedIn()) {
//			$uid = $_SESSION["userID"];
			$uname = $_SESSION["uname"];

			echo "<form action=login.php method=post>\n";
			echo "\t\tLogged in as $uname \n";
			echo "\t\t<input type=submit name=logout value=\"Log out\">\n";
			echo "\t</form>\n";
		} else {
			echo "<input type=button name=login value=\"Log in\" onclick=\"showlogin()\">";
		}

		echo "\n</div>";
	}

	function getUserID() {

		return $_SESSION["userID"];
	}
}

$sess = new sess;


?>
