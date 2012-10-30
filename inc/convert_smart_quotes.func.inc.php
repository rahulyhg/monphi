<?php
function convert_smart_quotes($string) 
{
	$search = array(
		chr(145),
		chr(146),
		chr(147),
		chr(148),
		chr(151)
		); 
	$replace = array(
		"&#8216;",
		"&#8217;", 
		'&#8220;', 
		'&#8221;', 
		'-'); 
	return str_replace($search, $replace, $string); 
}
?>