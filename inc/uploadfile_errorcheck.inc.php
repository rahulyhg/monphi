<?php
//http://www.php.net/manual/en/features.file-upload.errors.php
// input of $intVal should be $_FILES['upload_file']['error']
#function uploadfile_errorcheck($intVal)
function uploadfile_errorcheck($strErrorNumber, $boolEmpty=false)
{
	$strError = null;
	#switch ($_FILES[$strKey]['error'])
	switch ($strErrorNumber)
	{
		case 0:
			// There is no error, the file uploaded with success.
			break;
		case 1:
			$strError .= 'The uploaded file exceeds the upload_max_filesize directive in php.ini.<br />'."\n";
			break;
		case 2:
			$strError .= 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.<br />'."\n";
			break;
		case 3:
			$strError .= 'The uploaded file was only partially uploaded.<br />'."\n";
			break;
		case 4:
			if($boolEmpty)
			{
				$strError .= 'No file was uploaded.<br />'."\n";
			}
				break;
		case 6:
			$strError .= 'Missing a temporary folder.<br />'."\n";
			break;
		case 7:
			$strError .= 'Failed to write file to disk.<br />'."\n";
			break;
		default:
			if($boolEmpty)
			{
				$strError .= 'Unknown Error<br />'."\n";
			}
			break;
	}
	// if first error checking didn't get anything lets check some more
	// check file size for value of 0.
	if ($strError == null)
	{
		if ($_FILES[$strKey]['size'] == 0 && $boolEmpty)
		{
				$strError .= 'File is Empty (size of 0)<br />'."\n";
		}
		else
		{
			$strError = false;
		}
	}
	return $strError;
}
?>