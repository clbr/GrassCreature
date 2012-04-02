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
	<script src="js/js.js" type="text/javascript"></script>
</head>

<body class=lining>

<?php
	error_reporting(E_ALL);
	require_once('DatabaseOperation/idea.php');
	require_once('uploadFile.php');

	$ideaid = $_GET['ideaid'];

	// Gets the currently open idea's info.
	$ideaData = getIdeaInfo($ideaid);
	$idea = $ideaData->fetch_object();

	if (!$sess->isAdmin()) {
		echo "You are not an admin.";
	}
	else {
		if (!isset($_POST['submitChanges'])) {
		// Fields are shown when the page loads, after submit is pressed, fields go away and a success message is shown instead.

		// Fill textfields with existing data.
		// Note! in form action: send $ideaid in GET to this page itself.
		echo'
		<div id="ideaForms" class="IdeaAdd">
			<form method="POST" action="adminEditIdea.php?ideaid='.$ideaid.'" enctype="multipart/form-data">
				*Idea name:<br>
				<input type="text" id="IdeaName" name="IdeaName" value="'.$idea->Name.'"><br>
				*Idea description:<br>
				<TEXTAREA name="desc" rows="10" cols="70">'.$idea->Description.'</TEXTAREA><br>
				Cost estimation (&euro;)<br>
				<input type="text" id="CostEst" name="CostEst" value="'.$idea->Cost.'"><br>
				Additional information:<br>
				<TEXTAREA Name="AddInfo" rows="6" cols="70">'.$idea->AdditionalInfo.'</TEXTAREA><br>
				Request date/time frame for idea/implementation:<br>
				<input Name="ReqDate" rows="1" cols="20" value="'.$idea->RequestDate.'"><br>
				Based on idea ID (if any):<br>
				<input Name="BasedOn" rows="1" cols="20" value="'.$idea->BasedOn.'"><br>
				Status:<br>
				<select id="IdeaStatus" name="IdeaStatus">
					<option value="active" '; if ($idea->Status == 'active') { echo 'selected="selected"'; } echo '>Active</option>
					<option value="closed" '; if ($idea->Status == 'closed') { echo 'selected="selected"'; } echo '>Closed</option>
					<option value="in implementation" '; if ($idea->Status == 'in implementation') { echo 'selected="selected"'; } echo '>In implementation</option>
					<option value="implemented" '; if ($idea->Status == 'implementation') { echo 'selected="selected"'; } echo '>Implemented</option>
				</select><br>
				Attach image:<br>
				<input type="file" name="file" id="file"><br>
				<input type="submit" name="submitChanges" value="Submit changes">
			</form>
		</div>';
		}
		else {
			// Save old version to db...
			saveVersion($idea->IdeaID, $idea->Version, $idea->Status, $idea->Name, $idea->Description, $idea->RequestDate, $idea->Cost, $idea->AdditionalInfo,
				$idea->BasedOn, $idea->Inventor);

			// and edit the idea with new data.
			if ($idea->Status != $_POST['IdeaStatus']) // Needed to know to set StatusLastEdited in db.
				$status_changed = 1;
			else
				$status_changed = 0;

			$ideaID = adminEditIdea($ideaid, $_POST['IdeaStatus'], $status_changed, $_POST['IdeaName'], $_POST['desc'], $_POST['ReqDate'], $_POST['CostEst'], $_POST['AddInfo'], $_POST['BasedOn'],
				$idea->Version, $idea->Inventor);

			// Upload image if there are any. Uploads the image to a folder with the same name as the idea's id.
			if (!$_FILES['file']['type'] == "")
				uploadImage($ideaID);

			echo "<div class='IdeaEdit'>Idea with id ".$idea->IdeaID." succesfully edited.</div>";
		}
	}
?>

</body>
</html>
