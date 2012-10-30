<?php
function arrMysqlRealEscapeString($input)
{
	if (!is_array($input))
	{
		$input = mysql_real_escape_string($input);
	}
	else
	{
		foreach ($input as $key => $val) {
			if (is_array($input[$key])) {
				$input[$key] = $this->arrMysqlRealEscapeString($input[$key]);
			}
			else {
				$input[$key] = mysql_real_escape_string($input[$key]);
			}
		}
	}
	return $input;
}
?>
