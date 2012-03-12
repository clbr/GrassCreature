<?php
	function uploadImage($ideaID)
	{	
		// Check if the file is an image... Add file size restrictions etc. here.
		if ($_FILES["file"]["type"] == "image/jpeg" ||
			$_FILES["file"]["type"] == "image/jpg" ||
			$_FILES["file"]["type"] == "image/png" ||
			$_FILES["file"]["type"] == "image/gif")
		{
			
			// Did any errors occur...
			if ($_FILES["file"]["error"] > 0)
			{
				echo "Error: " . $_FILES["file"]["error"] . "<br>";
			}			
			
			else
			{
				// Echo file info...
				/*echo "Uploading file...: " . $_FILES["file"]["name"] . "<br>";
				echo "Type: " . $_FILES["file"]["type"] . "<br>";
				echo "Size: " . round(($_FILES["file"]["size"] / 1024), 2) . " Kb<br>";	*/			
				
				// If the idea does not yet have its own image folder, create it.
				if (!file_exists("userImages/" . $ideaID ))
					mkdir("userImages/" . $ideaID);
				
				// Save tmp file to desired location, first check that file doesnt already exist.
				if (file_exists("userImages/" . $ideaID . "/" . $_FILES["file"]["name"]))
				{
					echo "Error: A file already exists with the given filename.<br>";
				}
				else
				{
					move_uploaded_file($_FILES["file"]["tmp_name"], "userImages/" . $ideaID . "/" . $_FILES["file"]["name"]);
				}
			}
		}
		else
		{
			echo "Only images are allowed. You tried to upload a file of the type: " . $_FILES["file"]["type"];			
		}
	}
?>