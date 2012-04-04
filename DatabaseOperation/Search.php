<?php
error_reporting(E_ALL);
require_once("details.php");
function advancedSearch() {

$mysqli = db_connect();

if(isset($_POST['date']))
{$date = $_POST['date'];}
else
{$date = null;}

if($date!=null){
 print "<br><br><table border=0 class='highlight center'>\n";
 print "<tr><th>Idea name</th><th>Version</th><th>Description</th><th>
 Status</th><th> RequestDate</th><th>Added On</th><th>Addiotional Information</th><th>Inventor</th>
 </tr>\n";}


if(isset($_POST['tags']))
{$tag1 = $_POST['tags'];
$trim = trim($tag1);}
else
{$tag1 = null;
$trim = null;}
	// Splitting the $tag1 string into pieces

$pieces = explode(" ", $trim);
$count = count($pieces);

if(isset($_POST['inventor']))
{$inventor1 = $_POST['inventor'];}
else
{$inventor1 = null;}

if(isset($_POST['status']))
{$status1 = $_POST['status'];}
else
{$status1 = null;}

// Array for keeping list of idea IDs to prevent duplicates.
	$array2 = array(
		 9999
	);

	foreach($pieces as $keyword)
	{

	$keyword2 = "%".$keyword."%";


if(empty($_POST['inventor']) && empty($_POST['tags'])){
	$sql = "SELECT IdeaId, LEFT(Idea.Name, 100), Version, LEFT(Description, 100), Status, RequestDate, AddingDate, LEFT(AdditionalInfo, 100), Inventor, User.Name, UserID
						  FROM Idea, User
						  WHERE Status= (?)

						  ORDER BY AddingDate ";
						  if ($date == "Newest")
		{
		$sql .= "DESC";
		}

		else{
		$sql .= "ASC";
		}
						  $stmt = $mysqli->prepare($sql);
						  $stmt->bind_param("s",$status1);

						  }


if(empty($_POST['inventor']) && !empty($_POST['tags'])){
	$sql = "SELECT IdeaId, LEFT(Idea.Name, 100), Version, LEFT(Description, 100), Status, RequestDate, AddingDate, LEFT(AdditionalInfo, 100), Inventor, User.Name, UserID
						  FROM Idea, User
						  WHERE Status= (?)

						  AND (Description LIKE CONCAT('%',(?),'%')
						   OR AdditionalInfo LIKE CONCAT('%',(?),'%')
						   OR Idea.Name LIKE CONCAT('%',(?),'%'))
						  ORDER BY AddingDate ";
						  if ($date == "Newest")
		{
		$sql .= "DESC";
		}

		else{
		$sql .= "ASC";
		}
						  $stmt = $mysqli->prepare($sql);
						  $stmt->bind_param("ssss", $status1, $keyword2, $keyword2, $keyword2);
						  }


 if(!empty($_POST['inventor']) && empty($_POST['tags'])){
	$sql = "SELECT IdeaId, LEFT(Idea.Name, 100), Version, LEFT(Description, 100), Status, RequestDate, AddingDate, LEFT(AdditionalInfo, 100), Inventor, User.Name, UserID
						  FROM Idea, User
						  WHERE Status= (?)
						  AND User.Name LIKE CONCAT('%',(?),'%')


						  ORDER BY AddingDate ";
						  if ($date == "Newest")
		{
		$sql .= "DESC";
		}

		else{
		$sql .= "ASC";
		}
$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss",$status1, $inventor1);
  }



if(!empty($_POST['inventor']) && !empty($_POST['tags'])){
	$sql = "SELECT IdeaId, LEFT(Idea.Name, 100), Version, LEFT(Description, 100), Status, RequestDate, AddingDate, LEFT(AdditionalInfo, 100), Inventor, User.Name, UserID
						  FROM Idea, User
						WHERE Status= (?)

						  AND

						  (User.Name LIKE CONCAT('%',(?),'%')


						  AND
								(
						  Description LIKE CONCAT('%',(?),'%')

							OR

							AdditionalInfo LIKE CONCAT('%',(?),'%')
									OR Idea.Name LIKE CONCAT('%',(?),'%')
								)
)
						  ORDER BY AddingDate ";

if ($date == "Newest")
		{
		$sql .= "DESC";
		}

		else{
		$sql .= "ASC";
		}
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sssss", $status1, $inventor1, $keyword2, $keyword2, $keyword2);

						  }










	if (!$stmt) die ("NOOOOOO " . $mysqli->error);


$stmt->execute();

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

	if(!in_array($array['id'], $array2)){

	print "<tr><td>$array[name]</td><td>$array[version]</td><td>$array[desc]</td><td>$array[status]</td>
	<td>$array[datereq]</td><td>$array[dateadd]</td><td>$array[addinfo]</td><td>$array[inventor]</td>
	</tr>\n";
	}
	// Pushing the current idea's id into the idea id array
		array_push($array2, $array['id']);


}
// End of foreach
}
	print "</table>";


$stmt->close();

$mysqli->close();



}
?>
