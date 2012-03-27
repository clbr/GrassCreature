<?php require_once("session.php");

// This page requires login.
$sess->mustBeLoggedIn();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Ideabank</title>

	<link href="css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<script src="http://jquery-ui.googlecode.com/svn/tags/latest/ui/minified/i18n/jquery-ui-i18n.min.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
			<script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
			<script src="http://jquery-ui.googlecode.com/svn/tags/latest/external/jquery.bgiframe-2.1.2.js" type="text/javascript"></script>
			<script src="http://jquery-ui.googlecode.com/svn/tags/latest/ui/minified/i18n/jquery-ui-i18n.min.js" type="text/javascript"></script>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<base target=main>
</head>

<body>

<?php
	error_reporting(E_ALL);
	require_once('DatabaseOperation/idea.php');
	require_once('uploadFile.php');

	$category=getCategory();

	if (!isset($_POST['IdeaName'])) {
	// Fields are shown when the page loads, after submit is pressed, fields go away and a success message is shown instead.
	?>
	<div id="ideaForms" class="IdeaAdd">
		<form method="POST" action="addIdea.php" enctype="multipart/form-data" id=addideaform>
			*Idea name:<br>
			<input type="text" id="IdeaName" name="IdeaName"><br>

			*Idea description:<br>
			<TEXTAREA name="desc" rows="10" cols="70" id="IdeaDesc"></TEXTAREA><br>

			Cost estimation (&euro;)<br>
			<input type="text" id="CostEst" name="CostEst"><br>

			Additional information:<br>
			<TEXTAREA Name="AddInfo" rows="6" cols="70"></TEXTAREA><br>

			Request date/time frame for idea/implementation:<br>
			<input Name="ReqDate" rows="1" cols="20"><br>

			Based on idea ID (if any):<br>
			<input Name="BasedOn" rows="1" cols="20"><br>

			Attach images:<br>
			<div id=addimages>
			<input type="file" name="file[]" onchange='moreimages()'></div>


			Category:<br>
			<input type="text" id="category" name="category"/><br><br>


<?php
echo "<script language='JavaScript'>\n";

echo "$(function() {";


echo "var availableTags = new Array();\n";
$i=0;
foreach($category as $value)
{
echo "availableTags[$i] = '$value';\n";
$i++;
}
?>

		$( "#category" ).autocomplete({
			source: availableTags
		});
	});
	</script>



			<br>
			<input type=radio name=permissions value=opentoall checked> Viewable by all
			<input type=radio name=permissions value=restrict> Specify permissions
			<br>

			<br>
			<input type=button name="submitIdea" value="Submit idea" onclick='addidea_check()'>
		</form>
	</div>
	<?php
	}
	else {
		echo "<div class='IdeaAdd'>";

		$ideaID = addIdea($_POST['IdeaName'], $_POST['desc'], $_POST['ReqDate'], $_POST['CostEst'],
			$_POST['AddInfo'], $_POST['BasedOn'], $_POST['permissions'], $sess->getUserID());

                // User follows his own ideas by default.
                require_once('DatabaseOperation/user.php');
                userFollowIdea($ideaID, $sess->getUserID());

		// Upload image if there are any.
		if ($_FILES['file']['size'][0] != 0)
			uploadImages($ideaID);

		echo "<br><br>Idea succesfully added with the ID: $ideaID.<hr>";
		if ($_POST["permissions"] == "restrict") echo "The idea is only visible to you currently. <a href='perms.php?id=$ideaID'>Edit permissions here.</a>";
		echo "</div>\n";

		// Wait 5 secs, then redirect to the newly added idea
		echo "<script type=\"text/javascript\">
			setTimeout(function() {
			window.location = 'showIdea.php?id=$ideaID';
			}, 5000);</script>";
	}
?>

<script src="js/js.js" type="text/javascript"></script>

<script type="text/javascript">

function moreimages() {

	// Props to Taneli
	var arrlen = document.getElementsByName('file[]').length;
	if (arrlen >= 10) {
		return;
	}

	var send = document.getElementById('addimages');
	if (!send) return;

	var br = document.createElement('br');
	send.appendChild(br);

	var inp = document.createElement('input');
	inp.type = 'file';
	inp.name = 'file[]';
	inp.onchange = moreimages;
	send.appendChild(inp);
}

function addidea_check() {

	form = document.getElementById('addideaform');
	name = document.getElementById('IdeaName');
	desc = document.getElementById('IdeaDesc');

	if (name.value.length < 1 || desc.value.length < 1) {
		alert("Please fill all required fields (marked by *)");
		return;
	}

	form.submit();
}

</script>

</body>
</html>
