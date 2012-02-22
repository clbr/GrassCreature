<?php

require_once("session.php");

function out() {
	header("Location: index.php");
}

if (isset($_POST["logout"])) {
	session_destroy();
	out();
}

if (!isset($_POST["username"]) || !isset($_POST["password"]))
	out();

if ($_POST["username"] == "kalle" && $_POST["password"] == "6bdc92abe4c361537993fcd64f2d7278") {
	$_SESSION["isAdmin"] = false;
	$_SESSION["userID"] = 1337;
}

out();

?>
