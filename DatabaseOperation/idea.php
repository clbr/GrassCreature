<?php	
	require_once("details.php");

	
	function addIdea($name, $desc) {

		$mysqli = new mysqli("mysql.labranet.jamk.fi", "ideapankki", "0jWF)(p35j%J", "ideapankki_dev");
		if (mysqli_connect_errno()) {
		 printf("Connect failed: %s\n", mysqli_connect_error());
		 exit();
		}

		$sql = "INSERT INTO Idea (Name, Description, Version, Status, RequestDate, Inventor) VALUES ('$name', '$desc', 0, 'new', CURDATE(), 1)";
		$result = $mysqli->query($sql);
		if(!$result) {
		$mysqli->close();
		return 1; }

		$mysqli->close();
		return 0;

	}

	function addComment() {
		$mysqli = db_connect();
	}

?>
