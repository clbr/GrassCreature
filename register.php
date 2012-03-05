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

<?php

if (isset($_POST["username"])) {

	$cx = $_POST["captchax"];
	$cy = $_POST["captchay"];

	if (!$captcha->check($cx, $cy)) {
		$error = "Captcha failed";
		goto main;
	}

	//register_user();
	echo "<h2>Successfully registered! You may now log in.</h2>";

} else {

main:

if (isset($error)) {
	echo "<h3 class=error>$error</h3>";
}

?>

<form action=register.php method=post id=registerform>

	<h3>Required fields:</h3>

	<table border=0>
	<tr><td>Username:</td><td><input type=text size=40 name=username></td></tr>
	<tr><td>E-mail:</td><td><input type=text size=40 name=email></td></tr>
	<tr><td>Password:</td><td><input type=password size=40 name=password1></td></tr>
	<tr><td>Password again:</td><td><input type=password size=40 name=password2></td></tr>
	</table>

	<h3>Optional fields:</h3>

	<table border=0>
	<tr><td>Company:</td><td><input type=text size=40 name=company></td></tr>
	<tr><td>Company address:</td><td><input type=text size=40 name=companyaddress></td></tr>
	</table>

	<input type=hidden name=captchax>
	<input type=hidden name=captchay>

	<br><br>
	<h3>Click on the fish to submit:</h3>
	<img src=captcha.php?gen width=468 height=80 onclick="regform()" id=captchapic>
</form>


<script src=js/js.js></script>
<script>

function regform() {

	// First the captcha position
	var pos_x = event.offsetX ? (event.offsetX) :
		event.pageX-document.getElementById("captchapic").offsetLeft;
	var pos_y = event.offsetY ? (event.offsetY) :
		event.pageY-document.getElementById("captchapic").offsetTop;

	document.getElementsByName('captchax')[0].value = pos_x;
	document.getElementsByName('captchay')[0].value = pos_y;

	// Then the form checks
	var pw1 = document.getElementsByName('password1')[0].value;
	var pw2 = document.getElementsByName('password2')[0].value;
	if (pw1 != pw2) {
		alert("The passwords don't match");
		return;
	}

	var mail = document.getElementsByName('email')[0].value;
	if (mail.indexOf("@") == -1 || mail.indexOf(".") == -1) {
		alert("The e-mail address is invalid");
		return;
	}

	var uname = document.getElementsByName('username')[0].value;
	if (uname.length < 1 || mail.length < 1 || pw1.length < 1) {
		alert("Please fill all the required fields");
		return;
	}


	// Hereby submit
	var form = document.getElementsByName('captchax')[0].form;

	var pass = "ideabank" + form.username.value + form.password1.value;
	var final = md5(pass);

	form.password1.value = final;

	form.submit();
}

</script>

<?php
}
?>

</body>
</html>
