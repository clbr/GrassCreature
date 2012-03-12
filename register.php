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

	register_user($_POST["username"], $_POST["email"], $_POST["password1"], $_POST["company"], $_POST["companyaddress"]);
	echo "<h2>Successfully registered! You may now log in.</h2>";

} else {

main:

if (isset($error)) {
	echo "<script type=\"text/javascript\">alert(\"$error\"); window.history.back();</script>";
}

?>

<form action=register.php method=post id=registerform>

	<h2>Register an account:</h2>

	<table border=0>

	<tr><td colspan=2><h3>Required fields:</h3></td></tr>

	<tr><td>Username:</td><td><input type=text size=40 name=username onblur="reg_ajaxy()"> <span id=reg_namecheck></span></td></tr>
	<tr><td>E-mail:</td><td><input type=text size=40 name=email onblur="reg_emailtest()"> <span id=reg_emtest></span></td></tr>
	<tr><td>Password:</td><td><input type=password size=40 name=password1 onblur="reg_pwmatch()"> <span id=pw1_pic></span></td></tr>
	<tr><td>Password again:</td><td><input type=password size=40 name=password2 onblur="reg_pwmatch()"> <span id=pw2_pic></span></td></tr>

	<tr><td colspan=2><h3>Optional fields:</h3></td></tr>

	<tr><td>Company:</td><td><input type=text size=40 name=company></td></tr>
	<tr><td>Company address:</td><td><input type=text size=40 name=companyaddress></td></tr>
	</table>

	<input type=hidden name=captchax>
	<input type=hidden name=captchay>

	<br><br>
	<h3>Click on the fish <img src="img/fisu.png" width=55 height=33 alt="fish"> to submit:</h3>
	<img src="captcha.php?gen" width=468 height=80 onclick="regform(event)" id=captchapic alt=captcha>
</form>


<script src="js/js.js" type="text/javascript"></script>
<script type="text/javascript">

function regform(event) {

	event = event || window.event;

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

function reg_ajaxy() {

	var x = new XMLHttpRequest();

	var dest = document.getElementById('reg_namecheck');
	var uname = document.getElementsByName('username')[0].value;

	if (uname.length < 1) return;

	x.open("GET", "DatabaseOperation/register.php?check=" + uname, true);

	x.onreadystatechange = function() {
		if (x.readyState == 4) {
			dest.innerHTML = x.responseText;
		}
	}

	x.send(null);

	dest.innerHTML = "<img src=\"img/loading.gif\" width=16 height=16>";
}

function reg_pwmatch() {

	var pw1 = document.getElementsByName('password1')[0].value;
	var pw2 = document.getElementsByName('password2')[0].value;

	if (pw1 == pw2 && pw1.length > 1) {
		document.getElementById('pw1_pic').innerHTML = "<img src=\"img/success.png\" width=24 height=24>";
		document.getElementById('pw2_pic').innerHTML = "<img src=\"img/success.png\" width=24 height=24>";
	} else {
		document.getElementById('pw1_pic').innerHTML = "<img src=\"img/fail.png\" width=24 height=24>";
		document.getElementById('pw2_pic').innerHTML = "<img src=\"img/fail.png\" width=24 height=24>";
	}
}

function reg_emailtest() {

	var mail = document.getElementsByName('email')[0].value;
	if (mail.indexOf("@") == -1 || mail.indexOf(".") == -1) {
		document.getElementById('reg_emtest').innerHTML = "<img src=\"img/fail.png\" width=24 height=24>";
	} else {
		document.getElementById('reg_emtest').innerHTML = "<img src=\"img/success.png\" width=24 height=24>";
	}

}

</script>

<?php
}
?>

</body>
</html>
