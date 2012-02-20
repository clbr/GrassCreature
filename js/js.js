function showlogin() {
	var div = document.getElementById("loginbox");
	div.innerHTML = "<form action=login.php method=post>" +
			"<input type=text name=username length=20>" +
			" <input type=password name=password length=20>" +
			" <input type=submit value=\"Log in\">" +
			"</form>";
}
