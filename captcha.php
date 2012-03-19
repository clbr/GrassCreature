<?php
require_once('session.php');

//error_reporting(E_ALL);

class captcha {

	const fishw = 50;
	const fishh = 30;
	const fullw = 468;
	const fullh = 80;
	const difficulty = 4;

	private function fish() {

		$w = self::fishw;
		$h = self::fishh;

		$img = imagecreatetruecolor($w, $h) or die('Can\'t create image');

		$bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
		$fg = imagecolorallocate($img, 194, 255, 175);

		imagefill($img, 0, 0, $bg);
		imageantialias($img, true);

//		imagesetthickness($img, 4);

		imagepolygon($img, array(0, 0, 0, $h, $w/5, $h/2), 3, $fg);
		imageellipse($img, ($w/5) * 3, $h/2, ($w/5) * 4 - 1, $h - 1, $fg);

		$rot = rand(0, 360);

		return imagerotate($img, $rot, $bg);
	}

	function init() {

		$w = self::fullw;
		$h = self::fullh;

		$_SESSION['centerx'] = rand(40, $w - 40);
		$_SESSION['centery'] = rand(25, $h - 25);
	}

	function generate() {

		$w = self::fullw;
		$h = self::fullh;

		$fish = $this->fish();

		$img = imagecreatetruecolor($w, $h) or die('Can\'t create image');

		$bg = imagecolorallocatealpha($img, 61, 0, 80, 64);
		$fg = imagecolorallocate($img, 194, 255, 175);

		imagefill($img, 0, 0, $bg);
//		imageantialias($img, true);

		// Drawing
		imagecopy($img, $fish, $_SESSION['centerx'] - imagesx($fish)/2,
				$_SESSION['centery'] - imagesy($fish)/2,
				0, 0, imagesx($fish), imagesy($fish));

		$diff = self::difficulty;

		for ($i = 0; $i < $diff; $i++) {
			$tri = array();
			for ($j = 0; $j < 6; $j+=2) {
				$tri[$j] = rand(0, $w);
				$tri[$j + 1] = rand(0, $h);
			}
			imagepolygon($img, $tri, 3, $fg);

			$cx = rand(20, $w - 20);
			$cy = rand(20, $h - 20);
			$len = rand(10, 80);

			imageellipse($img, $cx, $cy, $len, $len/2, $fg);
		}


		// Send the pic
		imagealphablending($img, false);
		imagesavealpha($img, true);

		header('Content-type: image/png');
		imagepng($img);
		imagedestroy($img);

	}

	function check($x, $y) {

/*		if (count($_GET) < 1)
			return;

		$c = array_keys($_GET);
		$c = explode(',', $c[0]);*/

		if (!(is_numeric($x) && is_numeric($y)))
			return false;
//echo "You sent $x and $y<br>";
//echo "The fish is at " . $_SESSION['centerx'] . " and " . $_SESSION['centery'] . "<br>";

		$x -= $_SESSION['centerx'];
		$y -= $_SESSION['centery'];

		$lensqr = $x*$x + $y*$y;

//echo "The dist is $lensqr<br>";

		if ($lensqr < 900) // 30^2
			return true;
	}
}

$captcha = new captcha();

if (isset($_GET['gen'])) {
	$captcha->init();
	$captcha->generate();
}

?>
