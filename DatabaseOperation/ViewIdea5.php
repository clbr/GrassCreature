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
searchIdea();


?>



</body>
</html>