<?php
################################################################################
# File Name : index.php                                                        #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2011070400                                                         #
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
################################################################################
# Path Variable                                                                #
################################################################################
$strPath = '../';
################################################################################
# Includes                                                                     #
################################################################################
// form
if (file_exists($strPath."inc/form_process.class.inc.php")) { include_once ($strPath."inc/form_process.class.inc.php"); } else { echo 'failed to open form_process.class.inc.php'; exit;}
// database stuff
if (file_exists($strPath."conf/dbc.conf.php")) { include_once ($strPath."conf/dbc.conf.php"); } else { echo 'failed to open dbc.conf.php'; exit;}
if (file_exists($strPath."inc/dbc.class.inc.php")) { include_once ($strPath."inc/dbc.class.inc.php"); } else { echo 'failed to open dbc.class.inc.php'; exit;}
#if (file_exists("./sql/sql.inc.php")) { include_once ("./sql/sql.inc.php"); } else { echo 'failed to open sql.inc.php'; exit;}
// templates (for front page select)
if (file_exists($strPath."inc/tpl.class.inc.php")) { include_once ($strPath."inc/tpl.class.inc.php"); } else { echo 'failed to open tpl.class.inc.php'; exit;}
//if (file_exists($strPath."conf/select_template.conf.php")) { include_once ($strPath."conf/select_template.conf.php"); } else { echo 'failed to open select_template.conf.php'; exit;}
// 
if (file_exists($strPath."inc/arrmysqlrealescapestring.inc.php")) { include_once ($strPath."inc/arrmysqlrealescapestring.inc.php"); } else { echo 'failed to open arrmysqlrealescapestring.inc.php'; exit;}
#if (file_exists($strPath."inc/arrstripslashes.inc.php")) { include_once ($strPath."inc/arrstripslashes.inc.php"); } else { echo 'failed to open arrstripslashes.inc.php'; exit;}
################################################################################
# Variables                                                                    #
################################################################################
$strTemplateFile = 'index.tpl.html';
$intEnd = strrpos($_SERVER['PHP_SELF'], "/");
$intLength = ++$intEnd;
$strPHP_SELFFolder = substr($_SERVER['PHP_SELF'], 0, $intEnd);
if (!empty($_SERVER['HTTPS']))
	$strServer = "https://";
else
	$strServer = "http://";

$arrFormConf = array(
	'formFile' => "index.tpl.html",
	'validReferer' => array(
		'[PHP_SELF]',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'index.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder,
	),
	'fieldText' => array(
		'name' => 'Name :<br />',
		'email' => 'Email :<br />',
		'message' => 'Message :<br />',
		'captcha' => 'Enter text from security image :<br />',
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
################################################################################
# Reference Variables                                                          #
################################################################################
$refForm = new form_process($arrFormConf);
################################################################################
#                                                                              #
################################################################################
#echo "REQUEST : <pre>";print_r($_REQUEST);echo "</pre><br />\n";
#echo "POST : <pre>";print_r($_POST);echo "</pre><br />\n";
#echo "GET : <pre>";print_r($_GET);echo "</pre><br />\n";
#if (empty($_REQUEST))
if (array_key_exists('submit', $_REQUEST))
	$boolDataSent = true;
else
	$boolDataSent = false;
/*
if (array_key_exists('id', $_REQUEST))
	$boolEdit = true;
else
	$boolEdit = false;
*/
################################################################################
# If submission modify robots.txt                                              #
################################################################################
if ($boolDataSent)
{
	// run error checking
	$refForm->checkReferer();
	$refForm->checkValidation();
	// add additional error checking here
	// check error "list"
	if ($refForm->returnErrors())
	{
		// process form submission data if errors for form reprint
		$refForm->processInputData();
		echo $refForm->strTemplate;
	}
	else
	{
		//good referer etc.
		########################################################################
		# robots.txt write here                                                #
		########################################################################
		// read file
		if(!$fileHandle = fopen("robots.txt", "r"))
		{
			echo 'cannot read file "/setup/robots.txt"<br />';
			exit;
		}
		else
		{
			// store file in strTemplate
			#if (!empty($_SERVER['HTTPS']))
			#	$strServer = "https://";
			#else
			#	$strServer = "http://";
			#	$_SERVER['SERVER_NAME']
			#$strFolderURL = $strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder;
			$strFullURL = substr($_SERVER['HTTP_HOST'].$strPHP_SELFFolder, 0, -7);
			#echo "FullURL : ".$strFullURL; exit;
			#$strMonphiRoot = str_replace("setup/", "", $_SERVER['PHP_SELF']);
			#echo "monphi root : ".$strMonphiRoot; exit;
			$strTemplate = fread($fileHandle, filesize("robots.txt"));
			fclose($fileHandle);
			if(preg_match("/\[yoursitehere\]/", $strTemplate))
			{
				$strTemplate = str_replace("[yoursitehere]", $strFullURL, $strTemplate);
			}
			// write the robots.txt file.
			if(!$fileHandle = fopen("../robots.txt", "w"))
			{
				echo 'cannot write to "robots.txt"<br />';
				exit;
			}
			else
			{
				// store file in strTemplate
				fwrite($fileHandle, $strTemplate);
				fclose($fileHandle);
			}
		}
		########################################################################
		# Everything should be done at this point redirect to next page        #
		########################################################################
		$strFolderURL = $strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder;
		$strRedirect = $strFolderURL."dbsetup.php";
		#$strRedirect = $strFolderURL."conf.php";
		header('Location: '.$strRedirect);
		#echo 'done';
	}
}
################################################################################
#                                                                              #
################################################################################
else
{
	$refTPL = new tpl();
	$boolWrite = true;
	$arrContent['frmAction'] = $_SERVER['PHP_SELF'];
	$arrContent['errorMsg'] = "";
	$arrContent['content'] = "";
	$arrContent['continue'] = "";

	// check permissions
#	$intMonphiConf = substr(sprintf('%o', fileperms('../conf/monphi.conf.php')), -4);
	$intDbcConf = substr(sprintf('%o', fileperms('../conf/dbc.conf.php')), -4);
	$intRobots = substr(sprintf('%o', fileperms('../robots.txt')), -4);

	// check if writable, if so allow continue.
#	if (is_writable("../conf/monphi.conf.php"))
#		$arrContent['content'] .= 'Test writing to <span style="color:#4aa02c">conf/monphi.conf.php</span> - returned '.$intMonphiConf.' - <span style="color:#4aa02c">Success</span>'."<br />\n";
#	else
#	{
#		$arrContent['content'] .= 'Test writing to <span style="color:#c00">conf/monphi.conf.php</span> - returned '.$intMonphiConf.' - <span style="color:#c00">Fail</span>'."<br />\n";
#		$boolWrite = false;
#	}
	if (is_writable("../conf/dbc.conf.php"))
		$arrContent['content'] .= 'Test writing to <span style="color:#4aa02c">conf/dbc.conf.php</span> - returned '.$intDbcConf.' - <span style="color:#4aa02c">Success</span>'."<br />\n";
	else
	{
		$arrContent['content'] .= 'Test writing to <span style="color:#c00">conf/dbc.conf.php</span> - returned '.$intDbcConf.' - <span style="color:#c00">Fail</span>'."<br />\n";
		$boolWrite = false;
	}
	if (is_writable("../robots.txt"))
		$arrContent['content'] .= 'Test writing to <span style="color:#4aa02c">robots.txt</span> - returned '.$intRobots.' - <span style="color:#4aa02c">Success</span>'."<br />\n";
	else
	{
		$arrContent['content'] .= 'Test writing to <span style="color:#c00">robots.txt</span> - returned '.$intRobots.' - <span style="color:#c00">Fail</span>'."<br />\n";
		$boolWrite = false;
	}

	// change recheck button to submit button.
//$arrContent['continue'] = '<a class="button_continue" href="dbsetup.php" alt=""></a>';
	if ($boolWrite)
		$arrContent['continue'] = '<input class="button_continue" name="submit" type="submit" value="" />';
	else
		$arrContent['continue'] = '<a class="button_recheck_permissions" href="index.php" alt=""></a>';
	$refTPL->go($strTemplateFile, $arrContent);
	echo $refTPL->get_content();
}
?>
