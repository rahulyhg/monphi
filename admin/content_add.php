<?php
################################################################################
# File Name : content_add.php                                                  #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2012100200                                                         #
#                                                                              #
# Copyright :                                                                  #
#   Copyright 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012 Philip Allen      #
#   This program is distributed under the terms of the GNU General Public      #
#   License                                                                    #
#                                                                              #
#   This file is free software; you can redistribute it and/or modify          #
#   it under the terms of the GNU General Public License as published          #
#   by the Free Software Foundation; either version 2 of the License,          #
#   or (at your option) any later version.                                     #
#                                                                              #
#   This File is distributed in the hope that it will be useful,               #
#   but WITHOUT ANY WARRANTY; without even the implied warranty of             #
#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the              #
#   GNU General Public License for more details.                               #
#                                                                              #
#   You should have received a copy of the GNU General Public License          #
#   along with This File; if not, write to the Free Software                   #
#   Foundation, Inc., 51 Franklin St, Fifth Floor,                             #
#   Boston, MA  02110-1301  USA                                                #
#                                                                              #
# External Files:                                                              #
#                                                                              #
# General Information (algorithm) :                                            #
#                                                                              #
################################################################################
if (file_exists("auth.php")) { include_once ("auth.php"); } else { echo 'failed to open auth.php'; exit;}
$arrContent['admin'] = $strAdmin;
#echo "post :<pre>"; print_r($_POST); echo "</pre><br />\n";
#echo "session:<pre>"; print_r($_SESSION);echo"</pre><br />\n";
################################################################################
# Path Variable                                                                #
################################################################################
$strPath = '../';
################################################################################
# Includes                                                                     #
################################################################################
// form
if (file_exists($strPath."inc/form_process.class.inc.php")) { include_once ($strPath."inc/form_process.class.inc.php"); } else { echo 'failed to open form_process.class.inc.php'; exit;}
if (file_exists($strPath."inc/arrmysqlrealescapestring.inc.php")) { include_once ($strPath."inc/arrmysqlrealescapestring.inc.php"); } else { echo 'failed to open arrmysqlrealescapestring.inc.php'; exit;}
if (file_exists($strPath."inc/arrstripslashes.inc.php")) { include_once ($strPath."inc/arrstripslashes.inc.php"); } else { echo 'failed to open arrstripslashes.inc.php'; exit;}
// templates
if (file_exists($strPath."inc/tpl.class.inc.php")) { include_once ($strPath."inc/tpl.class.inc.php"); } else { echo 'failed to open tpl.class.inc.php'; exit;}
// database stuff
if (file_exists($strPath."conf/dbc.conf.php")) { include_once ($strPath."conf/dbc.conf.php"); } else { echo 'failed to open dbc.conf.php'; exit;}
if (file_exists($strPath."inc/dbc.class.inc.php")) { include_once ($strPath."inc/dbc.class.inc.php"); } else { echo 'failed to open dbc.class.inc.php'; exit;}
//----------------------------------------------------------------------------//
// arrFormConf - Form Configurations                                          //
//----------------------------------------------------------------------------//
$arrFormConf = array(
	'formFile' => "content_add.tpl.html",
	'validReferer' => array(
		'[PHP_SELF]',
	),
//	'tokenTimer' => '300',
	'fieldText' => array(
		'url' => 'URL Alias :',
		'template' => 'Template File Name:',
		'dateadded' => 'Creation Date (mysql format yyyy-mm-dd):',
		'datemod' => 'Modification Date (mysql format yyyy-mm-dd) :',
		'htmltitle' => 'Title (HTML Browser Window) :',
	),
	'fieldError' => array(
		'url' => 'URL Alias',
		'template' => 'Template File Name',
		'dateadded' => 'Creation Date',
		'datemod' => 'Modification Date',
		'htmltitle' => 'Title (HTML Browser Window)',
	),
	'validation' => array(
		'url' => array(
			'required',
		),
		'template' => array(
			'required',
		),
		'dateadded' => array(
			'required',
		),
		'datemod' => array(
			'required',
		),
		'htmltitle' => array(
			'required',
		),
	),
	'errorWrapper' => array(
		'0' => "\n".'<span style="color:#c00;">',
		'1' => "</span>\n"
	),
	'errorMsgWrapper' => array(
		'0' => "<br /><br />\n".'<div style="background-color:#ffffd5; border:3px #c00 solid; color:#c00; padding:5px;">'."\n\t".'<div style="font-size:1.5em; font-weight:bold; padding-bottom:.5em;"><img src="../img/monphi/logo.png" style="vertical-align:middle;" /> Uh oh, there seems to be a problem!</div>',
		'1' => "</div>\n"
	),
	'errorMsg' => array(
		'referer' => 'Invalid Referer [referer]- You must use the same form provided by the host processing it',
		'required' => '<b>[field]</b> is a required field and cannot be blank',
		'token' => '<b>Invalid security token</b> Commonly caused by reloading the page, try resubmitting the form with the form buttons',
		'tokentime' => 'Security token ran out of time. Resubmit the form to solve this problem.',
		'useragent' => '<b>Possible session hijacking</b>, form submission has been halted.'
	)
);
//----------------------------------------------------------------------------//
// End arrFormConf - Form Configurations                                      //
//----------------------------------------------------------------------------//
################################################################################
# Reference Variables                                                          #
################################################################################
$refForm = new form_process($arrFormConf);
################################################################################
#                                                                              #
################################################################################
if (array_key_exists('submit', $_REQUEST))
	$boolDataSent = true;
else
	$boolDataSent = false;
################################################################################
#                                                                              #
################################################################################
if ($boolDataSent)
{
	// run error checking
	$refForm->checkValidation();
	$refForm->checkReferer();
//	$refForm->checkToken();
	$refForm->checkUserAgent($refForm->strRandomSeed);
	if ($refForm->returnErrors())
	{
		// process form submission data if errors for form reprint
		$refForm->processInputData();
		#echo $refForm->strTemplate;
		// added this to take input on auth admin menu
		$refTPL = new tpl();
		$refTPL->go($refForm->strTemplate, $arrContent, false);
		echo $refTPL->get_content();
	}
	else
	{
		// moved connection above arrMysqlRealEscapeString
		// because I was getting an error message saying I didn't
		// have access to run it.
		$refDBC = new dbc($arrDBCAdmin);
		$refDBC->sql_connect();
		$strURL = preg_replace('/[^A-Za-z0-9-_.\/]/', '', $_REQUEST['url']);
		$strURL = mysql_real_escape_string($strURL);
		// request strip is nulled as we are taking the posts in the routine below here.
		if (get_magic_quotes_gpc())
			$_POST = arrstripslashes($_POST);
			#$_REQUEST = arrstripslashes($_REQUEST);
		#$_REQUEST = arrMysqlRealEscapeString($_REQUEST);
		########################################################################
		# Process input from arrData                                           #
		########################################################################
/*
		$strQuery = '
INSERT INTO page
	(url, boolIndex, boolError, template, htmltitle, pagetitle, content, includePath, contentNews, dateadded, datemod, boolBBCode, boolInclude, boolDisplayNews, boolArchiveNews, boolNewsPagetitle, boolNewsContent, boolNewsMore, changefreq, priority, boolSitemapHide)
VALUES
	("'.$strURL.'", "'.$_REQUEST['boolIndex'].'", "'.$_REQUEST['boolError'].'", "'.$_REQUEST['template'].'", "'.$_REQUEST['htmltitle'].'", "'.$_REQUEST['pagetitle'].'", "'.$_REQUEST['content'].'", "'.$_REQUEST['includePath'].'", "'.$_REQUEST['contentNews'].'", "'.$_REQUEST['dateadded'].'", "'.$_REQUEST['datemod'].'", "'.$_REQUEST['boolBBCode'].'", "'.$_REQUEST['boolInclude'].'", "'.$_REQUEST['boolDisplayNews'].'", "'.$_REQUEST['boolArchiveNews'].'", "'.$_REQUEST['boolNewsPagetitle'].'", "'.$_REQUEST['boolNewsContent'].'", "'.$_REQUEST['boolNewsMore'].'", "'.$_REQUEST['changefreq'].'", "'.$_REQUEST['priority'].'", "'.$_REQUEST['boolSitemapHide'].'")
';
*/
		$refDBC->query('alter table page AUTO_INCREMENT = 0'); // reset auto increment.
		$arrFields = array("url", "boolIndex", "boolError", "template", "htmltitle", "pagetitle", "content", "boolBBCode", "boolInclude", "includePath", "boolForm", "includeForm", "dateadded", "datemod");
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
			// we should be doing the arrstripslashes if get magic quotes....
			$strValues .= '"'.mysql_real_escape_string($_POST[$strValue]).'"';
			$strCount++;
		}
		$strQuery = "INSERT INTO page\n\t(".$strInsert.")\nVALUES\n\t(".$strValues.")";
		#echo "<pre>".$strQuery."</pre><br />\n";
		$refDBC->query($strQuery);
		$intPageId = mysql_insert_id();
		########################################################################
		# Insert Module Content                                                #
		########################################################################
		$strQuery = '
SELECT id, path, content_file, content_db
FROM modules
WHERE content_tpl_file != ""
	&&
	boolEnable = "1"
ORDER BY name
';
		$resResult = $refDBC->query($strQuery);
		$intNumRows = mysql_num_rows($resResult);
		if ($intNumRows != 0)
		{
			while ($arrFetch = mysql_fetch_assoc($resResult))
			{
				#echo '<br /><br /><pre>'; print_r($arrFetch2); echo '</pre>';
				################################################################
				# update db content                                            #
				################################################################
				// should we remove any double periods from this? or double slashes (//)
				include('../'.$arrFetch['path'].$arrFetch['content_file']);
				$strFunction = $arrFetch['content_db'].'_insert';
				$strFunction($refDBC, $arrDBCAdmin, $intPageId);
				//$arrFetch = array_merge($arrFetch, $arrFetchMod);
			}
		}
		// if the mod is not enabled we still need to add the default data for it.
		$strQuery = '
SELECT id, path, content_file, content_db
FROM modules
WHERE content_tpl_file != ""
	&&
	boolEnable = "0"
ORDER BY name
';
		#echo $strQuery;
		$resResult = $refDBC->query($strQuery);
		$intNumRows = mysql_num_rows($resResult);
		if ($intNumRows != 0)
		{
			while ($arrFetch = mysql_fetch_assoc($resResult))
			{
				#print_r($arrFetch);
				$arrDir = explode("/", $arrFetch['path']);
				#echo 'arrDir<pre>';print_r($arrDir); echo '</pre>';
				$strDir = $arrDir[1];
				if (is_file('../mod/'.$strDir.'/'.$strDir.'.info.php'))
				{
					include ('../mod/'.$strDir.'/'.$strDir.'.info.php');
					if (isset($moduleTableInsert))
					{
						// this is pulling the wrong id most likely grabbing the id from the module above.
						$moduleTableValues = str_replace("[pid]", $intPageId, $moduleTableValue);
					}
					$strQuery = $moduleTableInsert.$moduleTableValues;
					#echo "<pre>".$strQuery."</pre><br />\n";
					$refDBC->query($strQuery);
				}
			}
		}
		########################################################################
		# End Insert Module Content                                            #
		########################################################################

		# Process input from arrData                                           #
		#echo "REQUEST : <pre>";print_r($_REQUEST);echo "</pre><br />\n";
		$refDBC->sql_close();
		$intSlashPos = strrpos($_SERVER['PHP_SELF'], "/");
		$strReturn = substr($_SERVER['PHP_SELF'], 0, $intSlashPos+1);
		$strMonphiRoot = str_replace("admin/content_add.php", "", $_SERVER['PHP_SELF']);

		$arrContent['content'] = '
	<b>Content Add Submission Complete</b>
	<br /><br />
	<a href="'.$strReturn.'content.php">Return to Content</a>
	<br /><br />
	<a href="'.$strMonphiRoot.$_REQUEST['url'].'">View the page</a>
';
		$refTPL = new tpl();
		$strTemplateFile = 'submission_complete.tpl.html';
		$refTPL->go($strTemplateFile, $arrContent);
		echo $refTPL->get_content();
	}
}

else
{
/* 20110702
	#echo $refForm->strTemplateOrig;
	// added this to take input on auth admin menu
	$refTPL = new tpl();
	$refTPL->go($refForm->strTemplateOrig, $arrContent, false);
	echo $refTPL->get_content();
*/
	$arrContent['contentmodule'] = '';
	$refDBC = new dbc($arrDBCAdmin);
	$refDBC->sql_connect();
	$strQuery = '
SELECT id, path, content_tpl_file, content_file, content_db
FROM modules
WHERE content_tpl_file != ""
	&&
	boolEnable = "1"
ORDER BY name
';
	$resResult = $refDBC->query($strQuery); // failing here why? bad user/password
	$intNumRows = mysql_num_rows($resResult);
	if ($intNumRows != 0)
	{
		// two arrfetch is bad!
		while ($arrFetch = mysql_fetch_assoc($resResult))
		{
			################################################################
			# add to template                                              #
			################################################################
			// read file $arrFetch['content_tpl_file']
			//$arrContent['contentmodule'] .= 
			$strModFile = '../'.$arrFetch['path'].$arrFetch['content_tpl_file'];
			if(!$fileHandle = fopen($strModFile, "r"))
			{
				echo 'cannot read file "'.$strModFile.'"<br />';
				exit;
			}
			else
			{
				$arrContent['contentmodule'] .= fread($fileHandle, filesize($strModFile));
				fclose($fileHandle);
			}
			#echo $arrContent['contentmodule']; // this is good. merge into other file.
			################################################################
			# // add to template                                           #
			################################################################
		}
	}
	mysql_free_result($resResult);
	// set the stuff up here.. need to read the file then pass it to a string into arrFormConf then set boolFile
	$refTPL = new tpl();
	$refTPL->go($refForm->strTemplateOrig, $arrContent, false);
	echo $refTPL->get_content();
}
#echo "<pre>"; print_r($_REQUEST); echo "</pre>";
#echo "<pre>"; print_r($refForm->arrFields); echo "</pre>";
?>
