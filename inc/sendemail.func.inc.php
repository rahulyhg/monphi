<?php
####################################################################
# sendemail                                                        #
####################################################################
// http://php.net/manual/en/function.mail.php
// allow for more headers if desired.
function sendemail($strTo, $strSubject, $strMessage, $strFrom = "", $strCc = "")
{
	// add headers
	$strHeaders = "";
	if ($strFrom != "")
		$strHeaders .= 'From: '.$strFrom . "\r\n";
	if ($strCC != "")
		$strHeaders .= 'Cc: '. $strCc . "\r\n";
	$strHeaders . = 'X-Mailer: PHP/' .phpversion();
	// tag message with IP and date
	$strMessage .= "\n\n================================================================================\n".'Sent From : '.$_SERVER['REMOTE_ADDR']."\n".'Date Stamp : '.date("D M j G:i:s T Y")."\n";
	// send email
	$boolSentMail = mail($strTo, $strSubject, $strMessage, $strHeaders);
	echo $boolSentMail;
}











/*
	//this should handle the message collection harvesting.. along with grabbing the IP address and time stamp.
	foreach ($this->arrFormData as $strKey => $strVal)
	{
		$this->arrEmailData['message'] .= $strKey .' : '. $strVal."\n";
	}
	// append the IP of who filled out the form and attach date stamp
	$this->arrEmailData['message'] .= "\n\n=========================\n".
'Sent From : '.$_SERVER['REMOTE_ADDR']."\n".
'Date Stamp : '.date("D M j G:i:s T Y")."\n"
;
	// replace : with &#58; incase of email exploits
	// after this is que'd in the email system the &#58; may be converted to :
	// for the recieved email.
	foreach($this->arrEmailData as $strKey => $strVal)
	{
		$arrEmailData[$strKey] = str_replace(":", "&#58;", $strVal);
	}
	// replace from field with form data
	if(isset($this->arrEmailData['replace_from']))
	{
		if($this->arrEmailData['replace_from'] != null)
		{
			$this->arrEmailData['from'] = $this->arrFormData[$this->arrEmailData['replace_from']];
		}
	}
	// replace to field with form data
	if(isset($this->arrEmailData['replace_to']))
	{
		if($this->arrEmailData['replace_to'] != null)
		{
			$this->arrEmailData['to'] = $this->arrFormData[$this->arrEmailData['replace_to']];
		}
	}
	// ensure our message doesn't have a line with 70 characters and no breaks
	$this->arrEmailData['message'] = wordwrap($this->arrEmailData['message'], 70);
	// send out the email and set boolSentMail boolean to true or false depending if mail was sent to mail server properly
	$this->boolSentMail = mail($this->arrEmailData['to'], $this->arrEmailData['subject'], $this->arrEmailData['message'], "From: ".$this->arrEmailData['from']);



$to = "recipient@example.com";
$subject = "Hi!";
$body = "Hi,\n\nHow are you?";
if (mail($to, $subject, $body))
{
	echo("<p>Message successfully sent!</p>");
}
else
{
	echo("<p>Message delivery failed...</p>");
}

$to      = 'nobody@example.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: webmaster@example.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

//'Sent From : '.$_SERVER['REMOTE_ADDR']."\n".
//'Date Stamp : '.date("D M j G:i:s T Y")."\n"


}

# Anti-header-injection - Use before mail()
# By Victor Benincasa <vbenincasa(AT)gmail.com>

foreach($_REQUEST as $fields => $value) if(eregi("TO:", $value) || eregi("CC:", $value) || eregi("CCO:", $value) || eregi("Content-Type", $value)) exit("ERROR: Code injection attempt denied! Please don't use the following sequences in your message: 'TO:', 'CC:', 'CCO:' or 'Content-Type'.");
*/
?>