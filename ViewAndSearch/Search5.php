<?php
/*error_reporting(E_ALL);*/
function searchIdea() {

$mysqli = new mysqli("mysql.labranet.jamk.fi", "ideapankki", "0jWF)(p35j%J", "ideapankki_dev");
if (mysqli_connect_errno()) {
 printf("Connect failed: %s\n", mysqli_connect_error());
 exit();
}

$date = $_POST['date'];

$sql = "SELECT Name, LEFT(Description, 100), Status, RequestDate, Inventor
						  FROM Idea
						  WHERE Status= (?)
						  AND Inventor LIKE CONCAT('%',(?),'%')
						  AND Description LIKE CONCAT('%',(?),'%')
						  ORDER BY RequestDate ";
if ($date == "Newest")
		{
		$sql .= "ASC";
		}

		else{
		$sql .= "DESC";
		}

		$stmt = $mysqli->prepare($sql);

	if (!$stmt) die ("NOOOOOO " . $mysqli->error);



$stmt->bind_param("sss",$status1, $inventor1, $tag1);

$status1 = $_POST['status'];
$inventor1 = $_POST['inventor'];
$tag1 = $_POST['tags'];




$stmt->execute();


$stmt->bind_result($name, $desc, $stat, $datereq, $inventor);



$stmt->store_result();


if($date!=null){
 print"<table border=1>\n";
 print "<tr><td><strong>Idea name</strong></td><td><strong>Description</strong></td><td><strong>
 Status</strong></td><td> <strong>Date</strong></td><td><strong>Inventor</strong></td>
 </tr>\n";}
	while($stmt->fetch())
	{

	$name3 = $name;
    $desc3 = $desc;
    $status3 = $stat;
    $date3 = $datereq;
	$inventor3 = $inventor;

	print "<tr><td>$name3</td><td>$desc3</td><td>$status3</td><td>$date3</td><td>$inventor3</td>
	</tr>\n";
	}
	print "</table>";



$stmt->close();

$mysqli->close();


}


?>
