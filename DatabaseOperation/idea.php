<?php

$con = mysql_connect("mysql.labranet.jamk.fi","ideapankki","0jWF)(p35j%J");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  echo "FAIL";
  }

mysql_select_db("ideapankki_dev", $con);
$query = mysql_query('SELECT * FROM Idea');
$results = mysql_fetch_assoc($query);

echo $query;
echo 'The query returned ' . $results['Name'];

mysql_query("INSERT INTO Idea (Name, Description, Version, Status, RequestDate, Inventor) VALUES ('undefined', 'undefined', 0, 'new', CURDATE(), 0)");
echo "ADDED";

mysql_close($con);


?>