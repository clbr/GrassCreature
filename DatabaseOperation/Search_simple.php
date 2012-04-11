<?php
/*error_reporting(E_ALL);*/
require_once("details.php");
function searchIdea($tag1) {

$mysqli = db_connect();

$trim = trim($tag1);
// Splitting the $tag1 string into pieces (=keywords)

$pieces = explode(" ", $trim);
$count = count($pieces);



 print"<table border=0 class='highlight center longtext'>\n";
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


	$sql = "SELECT distinct IdeaId, LEFT(Idea.Name, 100), LEFT(Idea.Description, 100), Status, RequestDate, AddingDate, LEFT(AdditionalInfo, 100), Inventor, User.Name, UserID,
						char_length(Idea.Name), char_length(Idea.Description), char_length(AdditionalInfo)
						  FROM Idea, User, Category, Idea_has_Category
						  WHERE UserID = Inventor and Status != 'new' and Status != 'closed' and (Idea.Name LIKE CONCAT('%',(?),'%')
						  OR User.Name LIKE CONCAT('%',(?),'%')
						  OR Idea.Description LIKE CONCAT('%',(?),'%')
						  OR AdditionalInfo LIKE CONCAT('%',(?),'%')
						  OR (IdeaID = Idea_IdeaID and Category_CategoryID = CategoryID and Category.Name like concat('%',(?),'%')))
						  ORDER BY AddingDate DESC ";



	$stmt = $mysqli->prepare($sql) or die ($mysqli->error);

$stmt->bind_param("sssss",$keyword2, $keyword2, $keyword2, $keyword2, $keyword2);

$stmt->execute() or die($mysqli->error);

$stmt->bind_result($id, $name, $desc, $stat, $dateReq, $dateAdd, $addInfo, $inventor, $username, $uid, $namelen, $desclen, $addlen,$category);

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
				 "inventor"=>$inventor4,
				 
				);




//debuggaus
//var_dump($array);
//var_dump($array2);

// Checking if the current idea has already been added in the list (through checking its id against the id-array)
	if(!in_array($array['id'], $array2)){

	echo "<tr><td><a href='showIdea.php?id=$id'>$array[name]";
	if ($namelen > 99) echo "...";
	echo "</a></td><td><a href='showIdea.php?id=$id'>$array[desc]";
	if ($desclen > 99) echo "...";
	echo "</a></td><td>$array[status]</td>
	<td>$array[datereq]</td><td>$array[dateadd]</td><td>$array[addinfo]</td>;
	if ($addlen > 99) echo "...";
	echo "</td>";
	echo "<td><a href='showUser.php?id=$inventor'>$array[inventor]</a></td>
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
