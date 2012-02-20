<?php

require_once("session.php");

function out() {
	header("Location: index.php");
}

if (!isset($_POST["username"]) || !isset($_POST["password"]))
	out();

if ($_POST["username"] == "kalle" && $_POST["password"] == "pasi") {
	$_SESSION["isAdmin"] = false;
	$_SESSION["userID"] = 1337;
}

out();

?>
