<?php
$con = mysql_connect("mysql.labranet.jamk.fi","ideapankki","0jWF)(p35j%J");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("my_db", $con);

mysql_query("SHOW TABLES;");

mysql_close($con);
?>