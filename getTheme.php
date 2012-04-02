<?php
	function getTheme($userID) {
		require_once("DatabaseOperation/user.php");
		$theme = getUserTheme($userID);
		if ($theme == "default" || $theme == null)
			echo "style";
		else
			echo $theme;
	}
?>
