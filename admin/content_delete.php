<?php
################################################################################
# File Name : content_delete.php                                               #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2011053100                                                         #
#                                                                              #
# Copyright :                                                                  #
#   Copyright 2005, 2006, 2007, 2008, 2009, 2010, 2011 Philip Allen            #
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
################################################################################
# Session information                                                          #
################################################################################
#session_start();
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
// database stuff
if (file_exists($strPath."conf/dbc.conf.php")) { include_once ($strPath."conf/dbc.conf.php"); } else { echo 'failed to open dbc.conf.php'; exit;}
if (file_exists($strPath."inc/dbc.class.inc.php")) { include_once ($strPath."inc/dbc.class.inc.php"); } else { echo 'failed to open dbc.class.inc.php'; exit;}
#if (file_exists("./sql/sql.inc.php")) { include_once ("./sql/sql.inc.php"); } else { echo 'failed to open sql.inc.php'; exit;}
// templates
if (file_exists($strPath."inc/tpl.class.inc.php")) { include_once ($strPath."inc/tpl.class.inc.php"); } else { echo 'failed to open tpl.class.inc.php'; exit;}
################################################################################
# Variables                                                                    #
################################################################################
// to allow content.php to submit to this form.
$intEnd = strrpos($_SERVER['PHP_SELF'], "/");
$intLength = ++$intEnd;
$strPHP_SELFFolder = substr($_SERVER['PHP_SELF'], 0, $intEnd);
if (!empty($_SERVER['HTTPS']))
	$strServer = "https://";
else
	$strServer = "http://";

//----------------------------------------------------------------------------//
// arrFormConf - Form Configurations                                          //
//----------------------------------------------------------------------------//
$arrFormConf = array(
	'formFile' => "content_delete.tpl.html",
	'validReferer' => array(
		'[PHP_SELF]',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'content.php',
	),
	'fieldText' => array(
		'id' => 'Select a Page to Delete :',
	),
	'fieldError' => array(
		'id' => 'Select a Page to Delete',
	),
	'validation' => array(
		'id' => array(
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
		'required' => '<b>[field]</b> is a required field and cannot be blank.',
	)
);
//----------------------------------------------------------------------------//
// End arrFormConf - Form Configurations                                      //
//----------------------------------------------------------------------------//
################################################################################
# Reference Variables                                                          #
################################################################################
$refForm = new form_process($arrFormConf);
$refDBC = new dbc($arrDBCAdmin);
$refTPL = new tpl();
################################################################################
#                                                                              #
################################################################################
#echo "REQUEST : <pre>";print_r($_REQUEST);echo "</pre><br />\n";
#echo "POST : <pre>";print_r($_POST);echo "</pre><br />\n";
#echo "GET : <pre>";print_r($_GET);echo "</pre><br />\n";
#if (empty($_REQUEST))
if (array_key_exists('id', $_REQUEST))
	$boolDataSent = true;
else
	$boolDataSent = false;
################################################################################
# Final Delete to Database                                                     #
################################################################################
if ($boolDataSent)
{
	$refForm->checkReferer();
	$refForm->checkValidation();
	if ($refForm->returnErrors())
	{
		// process form submission data if errors for form reprint
		$refForm->processInputData();
		#echo $refForm->strTemplate;
		// added this to take input on auth admin menu
		#$refTPL = new tpl();
		$arrContent['content'] = generate_select($refDBC, $arrDBCAdmin);
	#$strTemplateFile = './content_delete.tpl.html';
	#$refTPL->go($strTemplateFile, $arrContent);
	#echo $refTPL->get_content();
		$refTPL->go($refForm->strTemplate, $arrContent, false);
		echo $refTPL->get_content();
	}
	else
	{
		$refDBC->sql_connect();
		if (get_magic_quotes_gpc())
			$strID = stripslashes($_REQUEST['id']);
		else
			$strID = $_REQUEST['id'];
		$strID = mysql_real_escape_string($strID);
		########################################################################
		# delete record                                                        #
		########################################################################
		$strQuery = '
DELETE
FROM page
WHERE id="'.$strID.'"
';
		#echo "<br /><br />".$strQuery."<br />\n";
		$refDBC->query($strQuery);
		$refDBC->query('alter table page AUTO_INCREMENT = 0');


		// delete from block_links table
		$strQuery = '
DELETE
FROM module_block_links
WHERE pid="'.$strID.'"
';
		#echo "<br /><br />".$strQuery."<br />\n";
		$refDBC->query($strQuery);
		$refDBC->query('alter table module_block_links AUTO_INCREMENT = 0');

		// delete from mod tables
		$strQuery = '
SELECT content_db
FROM modules
WHERE content_db != ""
';
		$resResult = $refDBC->query($strQuery);
		$intNumRows = mysql_num_rows($resResult);
		if ($intNumRows != 0)
		{
			while ($arrFetch = mysql_fetch_assoc($resResult))
			{
				#echo '<br /><br /><pre>';print_r($arrFetch);echo "</pre><br />\n";
				$strQuery = '
DELETE
FROM '.$arrFetch['content_db'].'
WHERE pid="'.$strID.'"
';
				#echo "<br /><br />".$strQuery."<br />\n";
				$refDBC->query($strQuery);
				$refDBC->query('alter table '.$arrFetch['content_db'].' AUTO_INCREMENT = 0');
			}
		}
		########################################################################
		# Process input from arrData                                           #
		########################################################################
		#echo "REQUEST : <pre>";print_r($_REQUEST);echo "</pre><br />\n";
		$refDBC->sql_close();
		#echo "data has been submitted!<br />\n";
		#echo '<a href="'.$_SERVER['PHP_SELF'].'">'.$_SERVER['PHP_SELF']."</a><br />\n";
		$intSlashPos = strrpos($_SERVER['PHP_SELF'], "/");
		$strReturn = substr($_SERVER['PHP_SELF'], 0, $intSlashPos+1);
		// if sent from content.php return to there.
		if ($_SERVER['HTTP_REFERER'] == $strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'content.php')
			header('Location: '.$_SERVER['HTTP_REFERER']);
		$arrContent['content'] = '
		<b>Content Deleted</b>
		<br /><br />
		<a href="'.$strReturn.'content.php">Return to Content</a>
		';
		$strTemplateFile = 'submission_complete.tpl.html';
		$refTPL->go($strTemplateFile, $arrContent);
		echo $refTPL->get_content();
	}
}
################################################################################
# collect data to populate forms                                               #
#    Select display list for editing                                           #
################################################################################
else
{
	$arrContent['frmAction'] = $_SERVER['PHP_SELF'];
	$arrContent['content'] = '';
	$arrContent['errorMsg'] = '';
	// list pages here
	$arrContent['content'] = generate_select($refDBC, $arrDBCAdmin);
	$strTemplateFile = './content_delete.tpl.html';
	$refTPL->go($strTemplateFile, $arrContent);
	echo $refTPL->get_content();
}
#echo "<pre>"; print_r($refForm->arrFields); echo "</pre>";


################################################################################
# Generate select from database                                                #
################################################################################
function generate_select($refDBC, $arrDBCAdmin)
{
	$refDBC->sql_connect();
	$strQuery = '
SELECT id, url
FROM page
ORDER BY url
';
	// create a select based on id > show 
	$resResult = $refDBC->query($strQuery);
	$intNumRows = mysql_num_rows($resResult);
	if ($intNumRows != 0)
	{
		$strReturn = '<select name="id" id="id" onkeydown="if ((event.which && event.which == 13) || (event.keyCode && event.keyCode == 13)) {document.getElementById(\'submit\').click();return false;}  else return true;">';
		while ($arrFetch = mysql_fetch_assoc($resResult))
		{
			$strReturn .= '<option value="'.$arrFetch['id'].'">'.$arrFetch['url'].'</option>';
		}
		$strReturn .= '</select>';
	}
	mysql_free_result($resResult);
	return $strReturn;
}