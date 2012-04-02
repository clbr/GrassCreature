<?php require_once("session.php");

// This page requires login.
$sess->mustBeLoggedIn();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Ideabank</title>

	<link href=
	<?php
		require_once("getTheme.php");
		getTheme($sess->getUserID());
	?>
 rel="stylesheet" type="text/css">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<base target=main>
</head>

<body class=lining>

<?php
	error_reporting(E_ALL);
	$userID = $_GET['userid'];

	// Only admins and the user himself can edit user's info.
	if ($userID == $sess->getUserID() || $sess->isAdmin()) {
		require_once('DatabaseOperation/user.php');
		$userData = getUserData($userID);
		$user = $userData->fetch(PDO::FETCH_OBJ);

		if (!isset($_POST['submitChanges'])) {
		// Fields are shown when the page loads, after submit is pressed, fields go away and a success message is shown instead.

		// Fill textfields with existing data.
		// Note! in form action: send $userID in GET to this page itself.
		echo'
		<div id="userForms" class="IdeaAdd">
			<form method="POST" action="editUser.php?userid='.$userID.'">
				Username: '.$user->Name.'<br><br>
				Email:<br>
				<input Name="Email" rows="1" cols="20" value="'.$user->Email.'"><br>
				Company:<br>
				<input Name="Company" rows="1" cols="20" value="'.$user->Company.'"><br>
				Company info:<br>
				<input Name="CompanyAddress" rows="1" cols="20" value="'.$user->CompanyAddress.'"><br>
				Site theme:<br>
				<select id="SelectedTheme" name="Theme">
					<option value="style" '; if ($user->SelectedTheme == 'style') { echo 'selected="selected"'; } echo '>Default</option>
					<option value="hellokitty" '; if ($user->SelectedTheme == 'hellokitty') { echo 'selected="selected"'; } echo '>Hello Kitty</option>
					<option value="teema" '; if ($user->SelectedTheme == 'teema') { echo 'selected="selected"'; } echo '>Teema</option>
				</select><br>
				<input type="submit" name="submitChanges" value="Submit changes">
			</form>
		</div>';
		}
		else {
			//<link href="css/style.css" rel="stylesheet" type="text/css">
			$theme = $_POST['Theme'];
			$email = htmlspecialchars($_POST['Email']);
			$company = htmlspecialchars($_POST['Company']);
			$compAddr = htmlspecialchars($_POST['CompanyAddress']);

			editUser($userID, $email, $company, $compAddr, $theme);

			echo "<div class='IdeaEdit'>User succesfully edited.</div>";
			echo "<script type=\"text/javascript\">
				setTimeout(function() {
				window.location = 'showUser.php?id=$userID';
				}, 2000);</script>";
		}
	}
	else
		echo "You don't have the permissions to edit this user.";
?>
</script>
<script src="js/js.js" type="text/javascript"></script>

</body>
</html>
