<html>
<head><title>Top101</title></head>
<body>

<h1></h1>
<?php
require_once("session.php");
require_once("details.php"); 
function(){
$yhteys = functiondb_connect(); 


$sql = "SELECT * FROM Idea ORDER BY AddingDate DESC";
$result = $mysqli->query($sql) or die($mysqli->error);
if($result){
echo"<table border='1'>";
$result->data_seek(0);
while($row = $result->fetch_row()){
echo"<tr><td>$row[0]</td>
<td>$row[1]</td>
<td>$row[2]</td>
<td>$row[3]</td>
<td>$row[4]</td>
<td>$row[5]</td>
<td>$row[6]</td>
<td>$row[7]</td>
<td>$row[8]</td>
<td>$row[9]</td><td>",$row[]+$row[]+$row[]+$row[]+$row[]+$row[]+$row[]+$row[]+$row[]+$row[],"</td>
</tr>\n";
}
echo "</table>";
$result->close();
}
else{echo "Error";}
$yhteys->close();
}
?>
</body>
</html>