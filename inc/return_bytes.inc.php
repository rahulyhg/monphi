<?php
/*
from http://www.php.net/manual/en/function.ini-get.php
used for converting php.ini settings Xbyte sizes to byte sizes
e.g. converts 8M to 8388608
*/
function return_bytes($val)
{
	$val = trim($val);
	$last = strtolower($val{strlen($val)-1});
	switch($last) {
		// The 'G' modifier is available since PHP 5.1.0
		case 'g':
			$val *= 1024;
		case 'm':
			$val *= 1024;
		case 'k':
			$val *= 1024;
	}

	return $val;
}
?>