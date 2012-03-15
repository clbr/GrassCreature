<?php
	function uploadImages($ideaID)
	{

		$len = count($_FILES["file"]["name"]);
		for ($i = 0; $i < $len; $i++) {

			if ($_FILES["file"]["size"][$i] == 0)
				continue;

			// Check if the file is an image... Add file size restrictions etc. here.
			$type = $_FILES["file"]["type"][$i];
			if (!($type == "image/jpeg" ||
				$type == "image/jpg" ||
				$type == "image/png" ||
				$type == "image/gif")) {

				echo "Only images are allowed. You tried to upload a file of the type: " . $type;
				return false;
			}


			// Errors?
			$err = $_FILES["file"]["error"][$i];
			if ($err > 0)
			{
				echo "Error: " . $err . "<br>";
				return false;
			}

			// If the idea does not yet have its own image folder, create it.
			if (!file_exists("userImages/" . $ideaID ))
				mkdir("userImages/" . $ideaID, 0777, true);

			// Save tmp file to desired location, first check that file doesnt already exist.
			if (file_exists("userImages/" . $ideaID . "/" . $_FILES["file"]["name"][$i]))
			{
				echo "Error: A file already exists with the given filename.<br>";
				return false;
			}
			else
			{
				move_uploaded_file($_FILES["file"]["tmp_name"][$i], "userImages/" . $ideaID . "/" . $_FILES["file"]["name"][$i]);
			}
		}

		return true;
	}
?>
