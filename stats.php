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

<body class=lining>

<h2>IdeaBank Statistics</h2>

<form method=post action=stats.php>

<table border=0>
<tr>
<td>Starting from &nbsp; &nbsp;</td>
<td><select name=period>
	<option value=30>30 days ago</option>
	<option value=60>60 days ago</option>
	<option value=90>90 days ago</option>
	<option value=180>six months ago</option>
	<option value=360>one year ago</option>
	<option value=300000>the start</option>
</select>
</td></tr>

<tr><td>
Chart all
</td><td>
<select name=what>
	<option value=comments>Comments</option>
	<option value=>Comments</option>
	<option value=comments>Comments</option>
</select>
</td></tr>

<tr><td colspan=2 style="text-align:center; padding: 0.5em;">
<input type=submit value="Go" name=submitbtn style="width: 50%;">
</td>
</tr>

</table>

</form>

<div id=chartdiv>
<?php
if (isset($_POST["submitbtn"])) {

	$what = $_POST["what"];
	$per = $_POST["period"];

	echo "<img src=chart.php?what=$what&period=$per>";
}
?>
</div>


</body>
</html>
