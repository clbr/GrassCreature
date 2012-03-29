<?php
require_once("session.php");

if (!isset($_GET["id"])) {
	echo "<script type=\"text/javascript\">" .
		"alert(\"No idea id given\"); window.history.back();" .
		"</script>";
	return;
}

$ideaID = $_GET["id"];

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
	<base target=main>
</head>

<body class=lining>

<?php

$uid = -1;
if ($sess->isLoggedIn())
	$uid = $sess->getUserID();

/* Get old versions of the idea*/

if ($sess->isAdmin()) {
	require_once("DatabaseOperation/idea.php");
	$versions = getVersions($ideaID);
	//echo "<pre>"; var_dump($versions); echo "</pre><br><br>";

	for ($i = 0; $i < count($versions); $i++) {
	
		// Print idea version unless activateVersion -button is pressed.
		if (!isset($_POST["activateVersion" . $versions[$i]->Version])) {
		
			echo "<div id=idea" . $versions[$i]->Version . " class=ideaboxtrans>\n" .			
				"<form method='POST' action='showVersions.php?id=" . $ideaID . "'>" .
				"<input type=hidden name=id value=hiddenplaceholder>\n" .
				"<table border=0 class=versions>\n" .
				"\t<tr><td>Version</td><td>" . $versions[$i]->Version . "</td></tr>\n";

			// Check if there is a change compared to the old version and color them accordingly. The DB can return a null value
			// if the data has not been initially set, so chek it first.
			if (isset($versions[$i+1]->Name) && ($versions[$i]->Name != $versions[$i+1]->Name)) {
				echo "\t<tr class='diffNew'><td>New Name</td><td>" . $versions[$i]->Name . "</td></tr>\n";
				echo "\t<tr class='diffOld'><td>Previous Name</td><td>" . $versions[$i+1]->Name . "</td></tr>\n";
			}
			else // Print dull unchanged data.
				echo "\t<tr><td>Name</td><td>" . $versions[$i]->Name . "</td></tr>\n";

			if (isset($versions[$i+1]->Description) && ($versions[$i]->Description != $versions[$i+1]->Description)) {
				echo "\t<tr class='diffNew'><td>New Description</td><td>" . $versions[$i]->Description . "</td></tr>\n";
				echo "\t<tr class='diffOld'><td>Previous Description</td><td>" . $versions[$i+1]->Description . "</td></tr>\n";
			}
			else
				echo "\t<tr><td>Description</td><td>" . $versions[$i]->Description . "</td></tr>\n";

			if (isset($versions[$i+1]->Status) && ($versions[$i]->Status != $versions[$i+1]->Status)) {
				echo "\t<tr class='diffNew'><td>New Status</td><td>" . $versions[$i]->Status . "</td></tr>\n";
				echo "\t<tr class='diffOld'><td>Previous Status</td><td>" . $versions[$i+1]->Status . "</td></tr>\n";
			}
			else
				echo "\t<tr><td id=idealeft>Status</td><td>" . $versions[$i]->Status . "</td></tr>\n";

			if (isset($versions[$i+1]->Cost) && ($versions[$i]->Cost != $versions[$i+1]->Cost)) {
				echo "\t<tr class='diffNew'><td>New Cost</td><td>" . $versions[$i]->Cost . "</td></tr>\n";
				echo "\t<tr class='diffOld'><td>Previous Cost</td><td>" . $versions[$i+1]->Cost . "</td></tr>\n";
			}
			else // Looks messy, but what happens is: If the idea field is set, print it, else print "NOT SET".
				echo "\t<tr><td>Cost</td><td>"; if (isset($versions[$i]->Cost)) { echo $versions[$i]->Cost; } else { echo "NOT SET"; } echo "</td></tr>\n";
			
			if (isset($versions[$i+1]->AdditionalInfo) && ($versions[$i]->AdditionalInfo != $versions[$i+1]->AdditionalInfo)) {
				echo "\t<tr class='diffNew'><td>New Addit. Info</td><td>" . $versions[$i]->AdditionalInfo . "</td></tr>\n";
				echo "\t<tr class='diffOld'><td>Previous Addit. Info</td><td>" . $versions[$i+1]->AdditionalInfo . "</td></tr>\n";
			}
			else {
				echo "\t<tr><td>Additional info</td><td>"; if (isset($versions[$i]->AdditionalInfo)) { echo $versions[$i]->AdditionalInfo; } else { echo "NOT SET"; } echo "</td></tr>\n";
			}
	
			if (isset($versions[$i+1]->BasedOn) && ($versions[$i]->BasedOn != $versions[$i+1]->BasedOn)) {
				echo "\t<tr class='diffNew'><td>New Based On</td><td>" . $versions[$i]->BasedOn . "</td></tr>\n";
				echo "\t<tr class='diffOld'><td>Previous Based On</td><td>" . $versions[$i+1]->BasedOn . "</td></tr>\n";
			}
			else {
				echo "\t<tr><td>Based on</td><td>"; if (isset($versions[$i]->BasedOn)) { echo "<a href=\"showIdea.php?id=" . $versions[$i]->BasedOn . "\">" .
					$versions[$i]->BasedOn . "</a>"; } else { echo "NOT SET"; } echo "</td></tr>\n";
			}
	
			if (isset($versions[$i+1]->RequestDate) && ($versions[$i]->RequestDate != $versions[$i+1]->RequestDate)) {
				echo "\t<tr class='diffNew'><td>New Req. date</td><td>" . $versions[$i]->RequestDate . "</td></tr>\n";
				echo "\t<tr class='diffOld'><td>Previous Req. date</td><td>" . $versions[$i+1]->RequestDate . "</td></tr>\n";
			}
			else {
				echo "\t<tr><td>Requested date</td><td>"; if (isset($versions[$i]->RequestDate)) { echo $versions[$i]->RequestDate; } else { echo "NOT SET"; } echo "</td></tr>\n";
			}
	
			if (isset($versions[$i+1]->AcceptedDate) && ($versions[$i]->AcceptedDate != $versions[$i+1]->AcceptedDate)) {
				echo "\t<tr class='diffNew'><td>New Accepted Date</td><td>" . $versions[$i]->AcceptedDate . "</td></tr>\n";
				echo "\t<tr class='diffOld'><td>Previous Accepted Date</td><td>" . $versions[$i+1]->AcceptedDate . "</td></tr>\n";
			}
			else {
				echo "\t<tr><td>Accepted date</td><td>"; if (isset($versions[$i]->AcceptedDate)) { echo $versions[$i]->AcceptedDate; } else { echo "NOT SET"; } echo "</td></tr>\n";
			}
	
			if (isset($versions[$i+1]->AddingDate) && ($versions[$i]->AddingDate != $versions[$i+1]->AddingDate)) {
				echo "\t<tr class='diffNew'><td>New Adding Date</td><td>" . $versions[$i]->AddingDate . "</td></tr>\n";
				echo "\t<tr class='diffOld'><td>Previous Adding Date</td><td>" . $versions[$i+1]->AddingDate . "</td></tr>\n";
			}
			else {
				echo "\t<tr><td>Added date</td><td>"; if (isset($versions[$i]->AddingDate)) { echo $versions[$i]->AddingDate; } else { echo "NOT SET"; } echo "</td></tr>\n";
			}
	
			if (isset($versions[$i+1]->Inventor) && ($versions[$i]->Inventor != $versions[$i+1]->Inventor)) {
				echo "\t<tr class='diffNew'><td>New Inventor</td><td><a href=\"showUser.php?id=" . $versions[$i]->Inventor . "\">" . $versions[$i]->Username . "</a></td></tr>\n";
				echo "\t<tr class='diffOld'><td>Previous Inventor</td><td>"; if (isset($versions[$i+1]->Inventor)) { echo "<a href=\"showUser.php?id=" . $versions[$i+1]->Inventor . "\">" .
					$versions[$i+1]->Username . "</a>"; } else { echo "NOT SET"; } echo "</td></tr>\n";
			}
			else {
				echo "\t<tr><td class=bottom>Inventor</td><td>"; if (isset($versions[$i]->Inventor)) { echo "<a href=\"showUser.php?id=" . $versions[$i]->Inventor . "\">" .
				$versions[$i]->Username . "</a>"; } else { echo "NOT SET"; } echo "</td></tr>\t";
			}

		//			"\t<tr><td></td><td></td></tr>\n" .
			
			echo "</table>";
			
			// Submit button for this version.
			echo "<input type='submit' name='activateVersion" . $versions[$i]->Version .
				"' value='Activate this version'></form>";
			
			echo "</div><br>\n";
			
		}
		else {
			//save version of the real current idea
			//update the idea with data from this version

			$currentVersion = getIdeaInfo($ideaID);
			$currentVer = $currentVersion->fetch_object();

			// Save current idea version as an old version to db...
			saveVersion($currentVer->IdeaID, $currentVer->Version, $currentVer->Status, $currentVer->Name, $currentVer->Description, $currentVer->RequestDate,
				$currentVer->Cost, $currentVer->AdditionalInfo, $currentVer->BasedOn, $currentVer->Inventor);

			// and replace its data with an old version. Remember to use current idea's version (incremented in the function).
			adminEditIdea($ideaID, $versions[$i]->Status, $versions[$i]->Name, $versions[$i]->Description, $versions[$i]->RequestDate, $versions[$i]->Cost,
				$versions[$i]->AdditionalInfo, $versions[$i]->BasedOn, $currentVer->Version, $versions[$i]->Inventor);


			echo "<script type='text/javascript'>
				window.location = 'showIdea.php?id=$ideaID&versionChanged=" . $versions[$i]->Version . "';
				</script>";

			//echo "version ".$versions[$i]->Version." activated dun dun dunnn";
		}
	}
}

?>

<script src="js/js.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js" type="text/JavaScript"></script>
<script type="text/JavaScript"></script>
</body>
</html>
