<?php

require_once("session.php");
require_once("DatabaseOperation/register.php");
require_once("captcha.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Ideabank</title>

	<link href="css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
</head>

<body>

<form action=register.php method=post id=registerform>

	<h3>Required fields:</h3>

	<table border=0>
	<tr><td>Username:</td><td><input type=text size=40 name=username></td></tr>
	<tr><td>E-mail:</td><td><input type=text size=40 name=email></td></tr>
	<tr><td>Password:</td><td><input type=password size=40 name=password></td></tr>
	<tr><td>Password again:</td><td><input type=password size=40 name=password2></td></tr>
	</table>

	<h3>Optional fields:</h3>

	<table border=0>
	<tr><td>Company:</td><td><input type=text size=40 name=company></td></tr>
	<tr><td>Company address:</td><td><input type=text size=40 name=companyaddress></td></tr>
	</table>

	<br><br>
	<h3>Click on the fish to submit:</h3>
	<img src=captcha.php?gen width=468 height=80 onclick="regform()">
</form>


<script src=js/js.js></script>
</body>
</html>
