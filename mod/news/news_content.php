<?php
// merge arr
// need to do an update string


// array merge.
#function News_select($refDBC, $arrDBCAdmin)
//mod is "content_db" field with _select appended to it.
################################################################################
# select                                                                       #
################################################################################
function mod_news_select($refDBC, $arrDBCAdmin)
{
	$strQuery = '
SELECT
	*
FROM
	mod_news
WHERE
	pid = "'.mysql_real_escape_string($_REQUEST['id']).'"
';
	$resResult = $refDBC->query($strQuery);
	$intNumRows = mysql_num_rows($resResult);
	if ($intNumRows != 0)
	{
		$arrFetch = mysql_fetch_assoc($resResult);
		mysql_free_result($resResult);
	return($arrFetch);
	}
}
################################################################################
# insert                                                                       #
################################################################################
function mod_news_insert($refDBC, $arrDBCAdmin)
{
	$arrFields = array('boolDisplayNews', 'boolArchiveNews', 'boolNewsPagetitle', 'boolNewsContent', 'boolNewsMore', 'contentNews');
	$strCount = 0;
	$strInsert = "";
	$strValues = "";
	foreach ($arrFields as $strValue)
	{
		if ($strCount != 0)
		{
			$strInsert .= ',';
			$strValues .= ', ';
		}
		$strInsert .= mysql_real_escape_string($strValue);
		// set string values
		if(array_key_exists($strValue, $_REQUEST))
		{
			$strValues .= '"'.mysql_real_escape_string($_POST[$strValue]).'"';
		}
		else
		{
			if (substr($strValue, 0, 4) == "bool")
			{
				$strValues .= '0';
			}
		}
		$strCount++;
	}
	// add ID
	$strInsert .= ', pid';
	$strValues .= ', '.mysql_insert_id();
	$strQuery = "INSERT INTO mod_news\n\t(".$strInsert.")\nVALUES\n\t(".$strValues.")";
	#echo "<pre>".$strQuery."</pre><br />\n";
	$refDBC->query($strQuery);
}
################################################################################
# update                                                                       #
################################################################################
function mod_news_update($refDBC, $arrDBCAdmin)
{
	$arrData = array('contentNews', 'boolDisplayNews', 'boolArchiveNews', 'boolNewsPagetitle', 'boolNewsContent', 'boolNewsMore');
	foreach ($arrData as $strValue)
	{
		if(array_key_exists($strValue, $_REQUEST))
		{
			$strQuery = '
UPDATE mod_news
SET '.mysql_real_escape_string($strValue).'="'.mysql_real_escape_string($_REQUEST[$strValue]).'"
WHERE pid = "'.mysql_real_escape_string($_REQUEST['id']).'"
';
			#echo $strQuery."<br />\n";
			$refDBC->query($strQuery);
		}
		else // else if no value then its a checkbox.. what if its a radio button or select.
		{
			$strQuery = '
UPDATE mod_news
SET '.mysql_real_escape_string($strValue).'="0"
WHERE pid = "'.mysql_real_escape_string($_REQUEST['id']).'"
';
			#echo $strQuery."<br />\n";
			$refDBC->query($strQuery);
		}
	}
}

?>