<?php
/*error_reporting(E_ALL);*/
require_once("details.php");
function searchIdea($tag1) {

$mysqli = db_connect();
	

// Splitting the $tag1 string into pieces (=keywords)

$pieces = explode(" ", $tag1);
$count = count($pieces);

	
 print"<table border=1>\n";
 print "<tr><td><strong>Idea name</strong></td><td><strong>Version</strong></td><td><strong>Description</strong></td><td><strong>
 Status</strong></td><td> <strong>RequestDate</strong></td><td><strong>Added On</strong></td><td><strong>Addiotional Information</strong></td><td><strong>Inventor</strong></td>
 </tr>\n";
 

// Array for keeping list of idea IDs to prevent duplicates.
	$array2 = array(
		 9999
	); 
	foreach($pieces as $keyword)
	{
	$keyword2 = "%".$keyword."%";
	
	
	$sql = "SELECT IdeaId, Idea.Name, Version, LEFT(Description, 100), Status, RequestDate, AddingDate, AdditionalInfo, Inventor, User.Name, UserID
						  FROM Idea, User
						  WHERE UserID = Inventor and (Idea.Name LIKE CONCAT('%',(?),'%')
						  OR User.Name LIKE CONCAT('%',(?),'%')
						  OR Description LIKE CONCAT('%',(?),'%')
						  OR AdditionalInfo LIKE CONCAT('%',(?),'%'))
						  ORDER BY AddingDate ";



	$stmt = $mysqli->prepare($sql) or die ($mysqli->error);

$stmt->bind_param("ssss",$keyword2, $keyword2, $keyword2, $keyword2);
	
$stmt->execute() or die($mysqli->error);

$stmt->bind_result($id, $name, $version, $desc, $stat, $dateReq, $dateAdd, $addInfo, $inventor, $username, $uid);

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
	$ideaid =$id;
	
$inventor4=$username;

// Array for the current idea
	$array = array(
				 "id" => $id,
				 "name" => $name,
				 "version"=>$version,
				 "desc"=>$desc,
				 "status"=>$stat,
				 "datereq"=>$dateReq,
				 "dateadd"=>$dateAdd,
				 "addinfo"=>$addInfo,
				 "inventor"=>$inventor4
				);
	
	


//debuggaus
//var_dump($array);
//var_dump($array2);

// Checking if the current idea has already been added in the list (through checking its id against the id-array)
	if(!in_array($array['id'], $array2)){
	
	print "<tr><td>$array[name]</td><td>$array[version]</td><td>$array[desc]</td><td>$array[status]</td>
	<td>$array[datereq]</td><td>$array[dateadd]</td><td>$array[addinfo]</td><td>$array[inventor]</td>
	</tr>\n";
	}
	// Pushing the current idea's id into the idea id array
		array_push($array2, $array['id']);
	
	}
		
		
	}
		
	print "</table>";	

	
$stmt->close();

$mysqli->close();	
	

}


?>
