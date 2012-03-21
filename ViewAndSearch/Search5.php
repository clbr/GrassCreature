<?php
error_reporting(E_ALL);
require_once("details.php");
function advancedSearch() {

$mysqli = db_connect();

$date = $_POST['date'];	

if($date!=null){	
 print"<table border=1>\n";
 print "<tr><td><strong>Idea name</strong></td><td><strong>Version</strong></td><td><strong>Description</strong></td><td><strong>
 Status</strong></td><td> <strong>RequestDate</strong></td><td><strong>Added On</strong></td><td><strong>Addiotional Information</strong></td><td><strong>Inventor</strong></td>
 </tr>\n";}
 

$tag1 = $_POST['tags'];		
	// Splitting the $tag1 string into pieces

$pieces = explode(" ", $tag1);
$count = count($pieces);
$inventor1 = $_POST['inventor'];	
$status1 = $_POST['status'];
	foreach($pieces as $keyword)
	{	
		
	$keyword2 = "%".$keyword."%";
	$keyword3 =	 "%".$keyword."%";
		 						   				  
if(empty($_POST['inventor']) && empty($_POST['tags'])){
	$sql = "SELECT Name, Version, LEFT(Description, 100), Status, RequestDate, AddingDate, AdditionalInfo, Inventor
						  FROM Idea
						  WHERE Status= (?)
						 						   
						  ORDER BY AddingDate ";	
						  $stmt = $mysqli->prepare($sql);	
						  $stmt->bind_param("s",$status1);	

						  }
						
						  
if(empty($_POST['inventor']) && !empty($_POST['tags'])){
	$sql = "SELECT Name, Version, LEFT(Description, 100), Status, RequestDate, AddingDate, AdditionalInfo, Inventor
						  FROM Idea
						  WHERE Status= (?)
						  			   
						  AND (Description LIKE CONCAT('%',(?),'%')	
						   OR AdditionalInfo LIKE CONCAT('%',(?),'%')	)
						  ORDER BY AddingDate ";	
						  $stmt = $mysqli->prepare($sql);	
						  $stmt->bind_param("sss", $status1, $keyword2, $keyword3);	
						  }
						  
						  
 if(!empty($_POST['inventor']) && empty($_POST['tags'])){
	$sql = "SELECT Name, Version, LEFT(Description, 100), Status, RequestDate, AddingDate, AdditionalInfo, Inventor
						  FROM Idea
						  WHERE Status= (?)
						  AND Inventor	= 
						   				  (SELECT UserID FROM User WHERE Name LIKE CONCAT('%',(?),'%'))					   
						  	
						   
						  ORDER BY AddingDate ";
$stmt = $mysqli->prepare($sql);	

$stmt->bind_param("ss",$status1, $inventor1);	
  }
	

	
if(!empty($_POST['inventor']) && !empty($_POST['tags'])){
	$sql = "SELECT Name, Version, LEFT(Description, 100), Status, RequestDate, AddingDate, AdditionalInfo, Inventor
						FROM Idea
						WHERE Status= (?)
						  AND Inventor	= 
						   				  (SELECT UserID FROM User WHERE Name LIKE CONCAT('%',(?),'%'))		   
						  AND
								(		   
						  Description LIKE CONCAT('%',(?),'%')

							OR 
							
							AdditionalInfo LIKE CONCAT('%',(?),'%') 	
												  	)
						   
						  ORDER BY AddingDate ";
$stmt = $mysqli->prepare($sql);	
$stmt->bind_param("ssss", $status1, $inventor1, $keyword2, $keyword3);	
						  
						  }
			  
						  
	
				  
		 			  
						  
	if ($date == "Newest")
		{
		$sql .= "DESC";
		}

		else{
		$sql .= "ASC";
		}

		
  
	if (!$stmt) die ("NOOOOOO " . $mysqli->error);

		
		



 		


$stmt->execute();		

$stmt->bind_result($name, $version, $desc, $stat, $dateReq, $dateAdd, $addInfo, $inventor);

$stmt->store_result();


	while($stmt->fetch())
	{
	
	$name3 = $name;
	$version3 = $version;
    $desc3 = $desc;
    $status3 = $stat;
    $date3 = $dateReq;
	$date4 = $dateAdd;
	$addInfo4 = $addInfo;
	$inventor3 = $inventor;
	
			
	
	$sql2 = "SELECT Name
			FROM User
			WHERE UserID = ?
			";
						  
	$stmt2 = $mysqli->prepare($sql2);	
	
	$stmt2->bind_param("s",$inventor3);

	$stmt2->execute();		

$stmt2->bind_result($iName);

$stmt2->store_result();
	
$stmt2->fetch();
$inventor4=$iName;	

	
	
	print "<tr><td>$name3</td><td>$version3</td><td>$desc3</td><td>$status3</td><td>$date3</td><td>$date4</td><td>$addInfo4</td><td>$inventor4</td>
	</tr>\n";
	}
	

}
	
	print "</table>";	
	
	
$stmt->close();

$mysqli->close();	
	

}


?>
