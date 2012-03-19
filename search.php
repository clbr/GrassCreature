<?php require_once("session.php"); ?>

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

<body>

<div id="ulko">

  <div id="banner">
    <h1>Search Ideas</h1>
  </div>

  <div id="View" class="IdeaView">


	<form action="ViewIdea5.php" name="SearchIdea" id="Search"  method="POST">
<select name="status">
<option value="New" selected="selected">New</option>
<option value="Active">Active</option>
</select>
<select name="date">
<option value="Newest" selected="selected">Newest</option>
<option value="Oldest">Oldest</option>
</select>
<label>Inventor</label> <input type="text" name="inventor"/>
<label>Word Search</label> <input type="text" name="tags"/>
<input type="submit" name="search" value="Search"/>

</div>

<?php

error_reporting(E_ALL);
require_once("Search5.php");
advancedSearch();

?>

<script src="js/js.js" type="text/javascript"></script>
</body>
</html>
