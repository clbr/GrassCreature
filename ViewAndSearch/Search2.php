<?php

function searchIdea() {

$mysqli = new mysqli("mysql.labranet.jamk.fi", "ideapankki", "0jWF)(p35j%J", "ideapankki_dev");
if (mysqli_connect_errno()) {
 printf("Connect failed: %s\n", mysqli_connect_error());
 exit();
}

		$status1 = $_POST["status"];
		$inventor1 = $_POST["inventor"];
		$date = $_POST["date"];
		
		
		if($date == "Newest"){
		
			if($inventor1!=NULL){
			$sql = "SELECT Name, Description, Status, RequestDate, Inventor
			FROM Idea
			WHERE Status='$status1'
			AND Inventor='$inventor1'
			ORDER BY RequestDate
			";}
		
			else{
			$sql = "SELECT Name, Description, Status, RequestDate, Inventor
			FROM Idea
			WHERE Status='$status1'
			ORDER BY RequestDate
			";}
		}
		else 
		{
		if($inventor1!=NULL){
			$sql = "SELECT Name, Description, Status, RequestDate desc, Inventor
			FROM Idea
			WHERE Status='$status1'
			AND Inventor='$inventor1'
			ORDER BY RequestDate DESC
			";}
		
			else{
			$sql = "SELECT Name, Description, Status, RequestDate, Inventor
			FROM Idea
			WHERE Status='$status1'
			ORDER BY RequestDate DESC
			";}
		}
		
		
					
		
$result = $mysqli->query($sql);

$mysqli->close();



if($result)
{
 echo "<table border='1'>\n";
  echo "<tr><th>Idea</th><th>Description</th><th>Status</th><th>Date</th><th>Inventor</th></tr>\n";
  while ($r = $result->fetch_array(MYSQLI_ASSOC)) {
    $name = $r["Name"];
    $desc = $r["Description"];
    $status = $r["Status"];
    $date = $r["RequestDate"];
	$inventor = $r["Inventor"];
    echo "<tr><td>$name</td><td>$desc</td><td>$status</td><td>$date</td><td>$inventor</td></tr>\n";
}

}


}



?>
