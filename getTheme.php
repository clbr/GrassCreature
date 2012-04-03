<?php
	function getTheme() {

		global $sess;

		$theme = "style";

		if ($sess->isLoggedIn()) {

			require_once("DatabaseOperation/user.php");
			$theme = getUserTheme($sess->getUserID());
			if ($theme == "default" || $theme == null)
				$theme="style";
		}

		echo "href=\"css/$theme.css\"";
	}
?>
