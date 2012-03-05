<?php

require_once("session.php");
require_once("DatabaseOperation/details.php");

function out() {
	header("Location: index.php");
}

if (isset($_POST["logout"])) {
	session_destroy();
	out();
}

if (!isset($_POST["username"]) || !isset($_POST["password"]))
	out();

/*
if ($_POST["username"] == "kalle" && $_POST["password"] == "6bdc92abe4c361537993fcd64f2d7278") {
	$_SESSION["isAdmin"] = false;
	$_SESSION["userID"] = 1337;
}*/


$u = $_POST["username"];
$p = $_POST["password"];

$db = db_connect();

$st = $db->prepare("select Name, PwdHash, UserID from User where Name=?");
if (!$st)
	die($db->error);

$st->bind_param("s", $u);

if (!$st->execute())
	die($db->error);

$st->bind_result($dbname, $dbpwd, $dbid);

if ($st->fetch() && $dbpwd == $p) {
	$_SESSION["isAdmin"] = false; // TODO: add check when the admin group is in place
	$_SESSION["userID"] = $dbid;
	$_SESSION["uname"] = $u;
}

$db->close();

out();

?>
