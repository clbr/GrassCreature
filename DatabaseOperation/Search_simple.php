<?php
/*error_reporting(E_ALL);*/
require_once("details.php");
function searchIdea($tag1) {

$mysqli = db_connect();

$trim = trim($tag1);
// Splitting the $tag1 string into pieces (=keywords)

$pieces = explode(" ", $trim);
$count = count($pieces);


 print"<table border=0 class='highlight center'>\n";
 print "<tr><th>Idea name</th><th>Description</th><th>
 Status</th><th>RequestDate</th><th>Added On</th><th>Additional Information</th><th>Inventor</th>
 </tr>\n";


// Array for keeping list of idea IDs to prevent duplicates.
	$array2 = array(
		 9999
	);
	foreach($pieces as $keyword)
	{
	$keyword2 = "%".$keyword."%";


	$sql = "SELECT IdeaId, LEFT(Idea.Name, 100), LEFT(Description, 100), Status, RequestDate, AddingDate, LEFT(AdditionalInfo, 100), Inventor, User.Name, UserID
						  FROM Idea, User
						  WHERE UserID = Inventor and Status != 'new' and Status != 'closed' and (Idea.Name LIKE CONCAT('%',(?),'%')
						  OR User.Name LIKE CONCAT('%',(?),'%')
						  OR Description LIKE CONCAT('%',(?),'%')
						  OR AdditionalInfo LIKE CONCAT('%',(?),'%'))
						  ORDER BY AddingDate ";



	$stmt = $mysqli->prepare($sql) or die ($mysqli->error);

$stmt->bind_param("ssss",$keyword2, $keyword2, $keyword2, $keyword2);

$stmt->execute() or die($mysqli->error);

$stmt->bind_result($id, $name, $desc, $stat, $dateReq, $dateAdd, $addInfo, $inventor, $username, $uid);

$stmt->store_result();



	while($stmt->fetch())
	{

	$name3 = $name;
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

	print "<tr><td>$array[name]...</td><td>$array[desc]...</td><td>$array[status]</td>
	<td>$array[datereq]</td><td>$array[dateadd]</td><td>$array[addinfo]...</td><td>$array[inventor]</td>
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
