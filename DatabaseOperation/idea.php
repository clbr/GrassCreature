<?php

function addIdea() {

$con = mysql_connect("mysql.labranet.jamk.fi","ideapankki","0jWF)(p35j%J");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("my_db", $con);


mysql_query("INSERT INTO Idea (Name, Description, Version, Status, RequestDate, Inventor) VALUES ('undefined', 'undefined', 0, 'new', CURDATE(), 0);");


mysql_close($con);

}

?>