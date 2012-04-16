<?php require_once("session.php"); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Ideabank</title>

	<link
	<?php
		require_once("getTheme.php");
		getTheme();
	?>
 rel="stylesheet" type="text/css">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<base target=main>
</head>

<?php
if (isset($_POST["what"])) {
	$what = $_POST["what"];
	$per = $_POST["period"];
	$line = $_POST["line"];
} else {
	$what = "";
	$per = 0;
	$line = "";
}
?>

<body class=lining>

<h2>IdeaBank Statistics</h2>

<form method=post action=stats.php>

<table border=0>
<tr>
<td>Starting from &nbsp; &nbsp;</td>
<td><select name=period>
	<option value=7 <?php if ($per == 7) echo "selected"; ?>>7 days ago</option>
	<option value=14 <?php if ($per == 14) echo "selected"; ?>>14 days ago</option>
	<option value=30 <?php if ($per == 30) echo "selected"; ?>>30 days ago</option>
	<option value=60 <?php if ($per == 60) echo "selected"; ?>>60 days ago</option>
	<option value=90 <?php if ($per == 90) echo "selected"; ?>>90 days ago</option>
	<option value=180 <?php if ($per == 180) echo "selected"; ?>>Six months ago</option>
	<option value=360 <?php if ($per == 360) echo "selected"; ?>>One year ago</option>
	<option value=3650 <?php if ($per == 3650) echo "selected"; ?>>The start</option>
</select>
</td></tr>

<tr><td>
Chart all
</td><td>
<select name=what>
	<optgroup label="Comments">
		<option value=comments <?php if ($what == "comments") echo "selected"; ?>>Comments</option>
	</optgroup>
	<optgroup label="Users">
		<option value=userstotal <?php if ($what == "userstotal") echo "selected"; ?>>Total users</option>
		<option value=userjoined <?php if ($what == "userjoined") echo "selected"; ?>>Users joined</option>
	</optgroup>
	<optgroup label="Ideas">
		<option value=ideastotal <?php if ($what == "ideastotal") echo "selected"; ?>>Total ideas</option>
		<option value=ideasinimplementation <?php if ($what == "ideasinimplementation") echo "selected"; ?>>Ideas in implementation</option>
		<option value=ideascorporated <?php if ($what == "ideascorporated") echo "selected"; ?>>Ideas incorporated</option>
	</optgroup>
	<optgroup label="Groups">
		<option value=groupstotal <?php if ($what == "groupstotal") echo "selected"; ?>>Total groups</option>
	</optgroup>
</select>
</td></tr>


<tr><td>
Line format
</td><td>
<select name=line>
	<option value=smooth <?php if ($line == "smooth") echo "selected"; ?>>Smooth</option>
	<option value=direct <?php if ($line == "direct") echo "selected"; ?>>Direct</option>
</select>
</td></tr>

<tr><td colspan=2 style="text-align:center; padding: 0.5em;">
<input type=submit value="Go" name=submitbtn style="width: 50%;">
</td>
</tr>

</table>

</form>

<div id=chartdiv style="padding: 1em;">
<?php
if (isset($_POST["submitbtn"])) {

	$what = $_POST["what"];
	$per = $_POST["period"];
	$line = $_POST["line"];

	echo "<img src=\"chart.php?what=$what&amp;period=$per&amp;line=$line\" alt=stats>";
}
?>
</div>


</body>
</html>
