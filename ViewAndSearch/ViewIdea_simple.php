<html>


<head>
	<title>Ideabank</title>

	<link href="style.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

</head>

<body>

<div id="ulko">

  <div id="banner">
    <h1>Search Ideas</h1>
  </div>

  <div id="View" class="IdeaView">


	<form action="ViewIdea_simple.php" name="SearchIdea" id="Search"  method="POST">

<label>Word Search</label> <input type="text" name="tags"/>
<input type="submit" name="search" value="Search"/>

</div>

<?php
error_reporting(E_ALL);
require_once("Search_simple.php");
searchIdea();


?>



</body>
</html>