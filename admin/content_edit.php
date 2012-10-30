<?php
################################################################################
# File Name : content_edit.php                                                 #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2011070300                                                         #
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
// templates
if (file_exists($strPath."inc/tpl.class.inc.php")) { include_once ($strPath."inc/tpl.class.inc.php"); } else { echo 'failed to open tpl.class.inc.php'; exit;}
//----------------------------------------------------------------------------//
// arrFormConf - Form Configurations                                          //
//----------------------------------------------------------------------------//
$arrFormConf = array(
	'formFile' => "content_edit.tpl.html",
	'boolFile' => true,
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
		'required' => '<b>[field]</b> is a required field and cannot be blank.',
		'email' => 'The Email address could not be validated, please enter a valid email address.',
		'captcha' => 'Captcha text does not match the image, use the reset checkbox to reset the image.',
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
// we should instantiate refForm later... so we can mod the template file.
#$refForm = new form_process($arrFormConf);
$refDBC = new dbc($arrDBCAdmin);
$refTPL = new tpl();
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
if (array_key_exists('id', $_REQUEST))
	$boolEdit = true;
else
	$boolEdit = false;
################################################################################
# Final Edit to Database                                                       #
#   Process input and display submission complete page                         #
################################################################################
if ($boolDataSent)
{
	#echo '<br /><br /><pre>'; print_r($_REQUEST); echo '</pre>';
	$refForm = new form_process($arrFormConf);
	// run error checking
	$refForm->checkValidation();
	$refForm->checkReferer();
//	$refForm->checkToken();
	$refForm->checkUserAgent($refForm->strRandomSeed);
	if ($refForm->returnErrors())
	{
		// this causes issues as it doesn't render the edit forms etc. Should figure out a better
		// way to handle this (send it to the other look, break out?
		// process form submission data if errors for form reprint
		################################
		$refForm->processInputData();
		echo $refForm->strTemplate;
		#break;
	}
	else
	{
		#$_SESSION['captcha_valid'] = false;
		#$_SESSION['captcha_reset'] = true;
		#$refDBC = new dbc($arrDBCAdmin);
		$refDBC->sql_connect();
		if (get_magic_quotes_gpc())
			$_REQUEST = arrstripslashes($_REQUEST);
		#echo "REQUEST strip :<pre>"; print_r($_REQUEST); echo "</pre>";
		//$_REQUEST = arrMysqlRealEscapeString($_REQUEST);
		#echo "REQUEST escaped :<pre>"; print_r($_REQUEST); echo "</pre>";
		########################################################################
		# Process Module input                                                 #
		########################################################################
		// find all currently linked modules. If we can't find arraykey from _REQUEST
		// set to disabled. If we find a _REQUEST arraykey then we enable it.
		$strQuery = '
SELECT module_blocks.block, module_block_links.id
FROM module_block_links
LEFT JOIN module_blocks
	ON module_block_links.bid = module_blocks.id
WHERE module_block_links.pid = "'.mysql_real_escape_string($_REQUEST['id']).'"
';

// block
// sitemap
		#echo $strQuery."<br />\n";
		$resResult = $refDBC->query($strQuery);
		$intNumRows = mysql_num_rows($resResult);
		if ($intNumRows != 0)
		{
			while ($arrFetch = mysql_fetch_assoc($resResult))
			{
				if (array_key_exists("mod".$arrFetch['block'], $_REQUEST))
				{
					#echo "module ".$arrFetch['moduleName']." : checked<br />\n";
					$strQuery = '
UPDATE module_block_links
SET boolEnable = "1"
WHERE id = "'.mysql_real_escape_string($arrFetch['id']).'"
';
					#echo $strQuery."<br />\n";
					$refDBC->query($strQuery);
				}
				else
				{
					#echo "module ".$arrFetch['moduleName']." : un-checked<br />\n";
					$strQuery = '
UPDATE module_block_links
SET boolEnable = "0"
WHERE id = "'.mysql_real_escape_string($arrFetch['id']).'"
';
					#echo $strQuery."<br />\n";
					$refDBC->query($strQuery);
				}
			}
		}
		########################################################################
		# Process Module Link Input                                            #
		#   This must be ran after process module input otherwise it will      #
		#   automagically set the newly loaded module to disabled              #
		########################################################################
		#echo "add module : ".$_REQUEST['addmodule']."<br />\n";
		//$_REQUEST['id'];
		if ($_REQUEST['addmodule'] != "")
		{
			$strQuery = '
SELECT mid
FROM module_blocks
WHERE id = "'.mysql_real_escape_string($_REQUEST['addmodule']).'"
';
			$resResult2 = $refDBC->query($strQuery);
			$intNumRows = mysql_num_rows($resResult2);
			if ($intNumRows != 0)
			{
				$arrFetch2 = mysql_fetch_assoc($resResult2);
			}
			$strQuery = '
INSERT INTO module_block_links
	(pid, mid, bid, boolEnable)
VALUES("'.mysql_real_escape_string($_REQUEST['id']).'", "'.mysql_real_escape_string($arrFetch2['mid']).'", "'.mysql_real_escape_string($_REQUEST['addmodule']).'", "1")
';
			#echo $strQuery."<br />\n";
			$refDBC->query($strQuery);
		}
		########################################################################
		# Update Module Content                                                #
		########################################################################
		$strQuery = '
SELECT id, path, content_file, content_db
FROM modules
WHERE content_tpl_file != ""
	&&
	boolEnable = "1"
ORDER BY name
';
		// idiot run another query with the same variables! added 2 to resresult and arrfetch.......
		$resResult2 = $refDBC->query($strQuery);
		$intNumRows = mysql_num_rows($resResult2);
		if ($intNumRows != 0)
		{
			// two arrfetch is bad!
			while ($arrFetch2 = mysql_fetch_assoc($resResult2))
			{
				#echo '<br /><br /><pre>'; print_r($arrFetch2); echo '</pre>';
				################################################################
				# update db content                                            #
				################################################################
				// should we remove any double periods from this? or double slashes (//)
				include('../'.$arrFetch2['path'].$arrFetch2['content_file']);
				$strFunction = $arrFetch2['content_db'].'_update';
				$strFunction($refDBC, $arrDBCAdmin);
				//$arrFetch = array_merge($arrFetch, $arrFetchMod);
			}
		}
		########################################################################
		# Archive Information here                                             #
		########################################################################
		// duplicate data into page_archive table. Will have new primary key ID, but link 
		// to the pages primary key ID. Also add timestamp.
		########################################################################
		# Process input from arrData                                           #
		########################################################################
		$arrData = array('url', 'boolIndex', 'boolError', 'section', 'page', 'article', 'template', 'htmltitle', 'pagetitle', 'content', 'includePath', 'dateadded', 'datemod', 'boolBBCode', 'boolInclude');
//'contentNews', 'boolDisplayNews', 'boolArchiveNews', 'boolNewsPagetitle', 'boolNewsContent', 'boolNewsMore',
//'changefreq', 'priority', 'boolSitemapHide'
		foreach ($arrData as $strValue)
		{
			if(array_key_exists($strValue, $_REQUEST))
			{
				// this shouldn't be here.
				/*
				if ($strValue == 'url')
				{
					$strPage = $_REQUEST[$strValue];
					$_REQUEST['url'] = preg_replace('/[^A-Za-z0-9-_.\/]/', '', $_REQUEST['url']);
				}
				*/
				$strQuery = '
UPDATE page
SET '.mysql_real_escape_string($strValue).'="'.mysql_real_escape_string($_REQUEST[$strValue]).'"
WHERE id = "'.mysql_real_escape_string($_REQUEST['id']).'"
';
				#echo $strQuery."<br />\n";
				#$resResult = $refDBC->query($strQuery);
				#echo $strQuery."<br />\n";
				$refDBC->query($strQuery);
			}
			####################################################################
			# checkboxes must be processed different                           #
			#   They do not appear in the data unless checked                  #
			#   So we set them to zero here                                    #
			####################################################################
			else
			{
				if (substr($strValue, 0, 4) == "bool")
				{
					$strQuery = '
UPDATE page
SET '.mysql_real_escape_string($strValue).' = "0"
WHERE id = "'.mysql_real_escape_string($_REQUEST['id']).'"
';
					#echo $strQuery."<br />\n";
					$refDBC->query($strQuery);
				}
			}
		}
		# Process input from arrData                                           #
		#echo "REQUEST : <pre>";print_r($_REQUEST);echo "</pre><br />\n";
		$refDBC->sql_close();
		#echo "data has been submitted!<br />\n";
		#echo '<a href="'.$_SERVER['PHP_SELF'].'">'.$_SERVER['PHP_SELF']."</a><br />\n";
		$intSlashPos = strrpos($_SERVER['PHP_SELF'], "/");
		$strReturn = substr($_SERVER['PHP_SELF'], 0, $intSlashPos+1);
		$strMonphiRoot = str_replace("admin/content_edit.php", "", $_SERVER['PHP_SELF']);

		$arrContent['content'] = '
	<b>Content Edit Submission Complete</b>
	<br /><br />
	<a href="'.htmlspecialchars($strReturn).'content.php">Return to Content</a>
	<br /><br />
	<a href="'.htmlspecialchars($strMonphiRoot).htmlspecialchars($_REQUEST['url']).'">View the page</a>
	<br /><br />
	<a href="content_edit.php?id='.htmlspecialchars($_REQUEST['id']).'">Return to editing</a>
	<br /><br />

';
		$strTemplateFile = 'submission_complete.tpl.html';
		$refTPL->go($strTemplateFile, $arrContent);
		echo $refTPL->get_content();
	}
}
################################################################################
# Edit Form - collect data to populate form                                    #
#    Display the edit template page form. Grabs data to populate forms and     #
#    displays everything for user.                                             #
################################################################################
// check boxes aren't processing correctly with the arrdata push
// think of a way around this.. should we have a check for checkbox value
// of 0? along with if there is a request or not? yeah we should do that easy in the class.
else if($boolEdit)
{
	############################################################################
	# collect data to populate forms                                           #
	############################################################################
	$refDBC->sql_connect();
	$strQuery = '
SELECT
	*
FROM
	page
WHERE
	id = "'.preg_replace('/[^0-9]/', '', $_REQUEST['id']).'"
';
	#echo "strQuery : ".$strQuery."<br />\n";
	$resResult = $refDBC->query($strQuery);
	$intNumRows = mysql_num_rows($resResult);
	if ($intNumRows != 0)
	{
		$arrFetch = mysql_fetch_assoc($resResult);

		############################################################################
		# contentmodule                                                            #
		############################################################################
		$arrContent['contentmodule'] = '';
		$strQuery = '
SELECT id, path, content_tpl_file, content_file, content_db
FROM modules
WHERE content_tpl_file != ""
	&&
	boolEnable = "1"
ORDER BY name
';
		// idiot run another query with the same variables! added 2 to resresult and arrfetch.......
		$resResult2 = $refDBC->query($strQuery);
		$intNumRows = mysql_num_rows($resResult2);
		if ($intNumRows != 0)
		{
			// two arrfetch is bad!
			while ($arrFetch2 = mysql_fetch_assoc($resResult2))
			{
				################################################################
				# add to template                                              #
				################################################################
				// read file $arrFetch['content_tpl_file']
				//$arrContent['contentmodule'] .= 
				$strModFile = '../'.$arrFetch2['path'].$arrFetch2['content_tpl_file'];
				if(!$fileHandle = fopen($strModFile, "r"))
				{
					echo 'cannot read module file "'.$strModFile.'" try disabling any newly added modules.<br />';
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
				################################################################
				# get db content                                               #
				################################################################
				// should we remove any double periods from this? or double slashes (//)
				// the ID's being pulled from these are fucking up the submission ID
				include('../'.$arrFetch2['path'].$arrFetch2['content_file']);
				$strFunction = $arrFetch2['content_db'].'_select';
				#echo '<br /><br />$strFunction :'.$strFunction.'<br /><br />';
				// run function from module_content.php file. if data returned strip ID and merch with arrFetch
				$arrFetchMod = $strFunction($refDBC, $arrDBCAdmin);
				#echo '$arrFetchMod :'.$arrFetchMod.'<br /><br />';
				// if data is present returns an array, otherwise it returns null.
				// basically this means if the module was added after the page creation there might be no data. it will be added after the first edit, but until then we need to not pull data.
				if (isset($arrFetchMod))
				{
					if (array_key_exists('id', $arrFetchMod)) // fixes submission edit id changes
					{
						unset($arrFetchMod['id']);
					}
					#echo "<br /><br /><pre>"; print_r($arrFetchMod); echo '</pre>';
					$arrFetch = array_merge($arrFetch, $arrFetchMod);
				}
#echo '<br /><br />arrfetch<pre>'; print_r($arrFetch); echo '</pre><br />';
				################################################################
				# // get db content                                            #
				################################################################
			}
		}
		#echo "<br /><br />"; print_r($arrFetch);
		// now stash arrFetch into $refForm->arrRequest;
		#}
		mysql_free_result($resResult);

		// set the stuff up here.. need to read the file then pass it to a string into arrFormConf then set boolFile
		$refTPL = new tpl();
		$refTPL->go($arrFormConf['formFile'], $arrContent);
		$arrFormConf['formFile'] = $refTPL->get_content();

		
		#echo "<br />Here<br />".$arrFormConf['formFile'];
		$arrFormConf['boolFile'] = false;
// closer

		$refForm = new form_process($arrFormConf);

		// send database query to form class setArrRequest 
		$refForm->setArrRequest($arrFetch);
	}
	else
	{
		echo "Invalid Page to Edit";
		exit;
	}
		// this is wrong its not loading the boolFile.
#echo '<br /><br />refForm arrfetch<pre>'; print_r($refForm->arrRequest); echo '</pre><br />';
#echo $refForm->strTemplateOrig;
#echo '<pre>';print_r($refForm->arrFields);echo '</pre>';
	// process input should now process the database query from setArrRequest above.
	$refForm->processInputData();
	// display the new template YAY!
	#echo $refForm->strTemplate;
	############################################################################
	# Load linked modules select box                                           #
	############################################################################
	$strModules = '';
/*
	$strQuery = '
SELECT id, moduleName
FROM module
';
*/
	$strQuery = '
SELECT
	modules.name, module_blocks.block, module_blocks.id
FROM module_blocks
LEFT JOIN modules
	on module_blocks.mid = modules.id
WHERE modules.boolEnable = "1"
';

// create a select based on id > show 
	$resResult = $refDBC->query($strQuery);
	$intNumRows = mysql_num_rows($resResult);
	if ($intNumRows != 0)
	{
		$strModules .= '<tr><td colspan="2" class="adminheader"><br /><br /><br />LINK A BLOCK MODULE TO THIS PAGE</td></tr>';
		$strModules .= '
	<tr>
		<td style="padding:5px;">Choose a block module to link (Multiple blocks can be added with multiple edits) :
</td>
		<td style="padding:5px;">';
		$strModules .= '<select name="addmodule">';
		$strModules .= '<option value="">-- Choose a module to link --</option>';
		while ($arrFetch = mysql_fetch_assoc($resResult))
		{
			// add check here to see if we already have this block linked.

			$strQuery = '
SELECT pid, bid
FROM module_block_links
WHERE pid = "'.mysql_real_escape_string($_REQUEST['id']).'"
';
			$resResult2 = $refDBC->query($strQuery);
			$intNumRows = mysql_num_rows($resResult2);
			if ($intNumRows != 0)
				$arrFetch2 = mysql_fetch_assoc($resResult2);
			if ($arrFetch2['bid'] != $arrFetch['id'])
				$strModules .= '<option value="'.$arrFetch['id'].'">'.$arrFetch['name'].' - '.$arrFetch['block'].'</option>';
			#echo '<br /><br /> arrFetch2 :'.$arrFetch2['bid'].'<br />';
			#echo '<br /><br /> arrFetch :'.$arrFetch['id'].'<br />';
			#echo '<br /><br /> request :'.$_REQUEST['id'].'<br />';
		}
		$strModules .= '</select>';
		$strModules .= '
		</td>
	</tr>';
	}
	############################################################################
	# Enable disable loaded modules                                            #
	############################################################################
	// could we edit the strTemplate to add in a few more plugs for the modules.
	$strQuery = '
SELECT
	module_block_links.id, modules.name, module_blocks.block, modules.path, module_blocks.block_file, module_block_links.boolEnable
FROM module_block_links
LEFT JOIN module_blocks
	ON module_block_links.bid = module_blocks.id
LEFT JOIN modules
	on module_blocks.mid = modules.id
WHERE module_block_links.pid = "'.mysql_real_escape_string($_REQUEST['id']).'" &&
	modules.boolEnable = "1"
';

// id	name		block		path			block_file		boolEnable
// 1	Site Map	sitemap		mod/sitemap/	sitemap.php		1
	#echo "query : ".$strQuery."<br />\n";
	$resResult = $refDBC->query($strQuery);
	$intNumRows = mysql_num_rows($resResult);
	if ($intNumRows != 0)
	{
		#$strModules .= '<table cellspacing="0" padding="0">';
		$strModules .= '<tr><td colspan="2" class="adminheader"><br /><br /><br />ENABLE/DISABLE LINKED BLOCK MODULES</td></tr>';
		while ($arrFetch = mysql_fetch_assoc($resResult))
		{
			if ($arrFetch['boolEnable'] == "1")
				$strChecked = " checked";
			else
				$strChecked = "";
			$strModules .= '
	<tr>
		<td style="padding:5px;"><b>'.$arrFetch['name'].' - '.$arrFetch['block'].'</b><br />"'.$arrFetch['path'].$arrFetch['block_file'].'" :</td>
		<td style="padding:5px;"><input name="mod'.$arrFetch['block'].'" style="vertical-align:middle;padding-right:5px;" type="checkbox" value="1"'.$strChecked.' /></td>
	</tr>
';
		}
		#$strModules .= '</table>';
	}
	mysql_free_result($resResult);
	############################################################################
	#                                                                          #
	############################################################################
	#echo str_replace("[modules]", $strModules, $refForm->strTemplate);
	$arrContent['modules'] = $strModules;
	// added this to take input on auth admin menu
	############################################################################
	# contentmodule                                                            #
	############################################################################
	############################################################################
	# wrap up edit display                                                     #
	############################################################################
	$refDBC->sql_close();
#echo "<br /><br /><pre>";print_r($arrContent); echo "</pre>";
	$refTPL = new tpl();
	$refTPL->go($refForm->strTemplate, $arrContent, false);
	echo $refTPL->get_content();

}
################################################################################
# Default display generate select form                                         #
#    Display entry select dropdown list to determin the page to edit           #
################################################################################
else
{
	$arrContent['frmAction'] = $_SERVER['PHP_SELF'];
	$arrContent['content'] = "";
	// list pages here
	$refDBC->sql_connect();
/*
# old style removed 20110511
	$strQuery = '
SELECT id, section, page, article
FROM page
ORDER BY section, page, article
';
*/
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
		/* javascript code
		http://www.victorchen.info/submit-form-on-enter-key-solution/
		http://www.allasp.net/enterkey.aspx
		onkeydown="if ((event.which && event.which == 13) || (event.keyCode && event.keyCode == 13)) {document.getElementById('submit').click();return false;}  else return true;"
		*/																							
		$arrContent['content'] .= '<select name="id" id="id" onkeydown="if ((event.which && event.which == 13) || (event.keyCode && event.keyCode == 13)) {document.getElementById(\'submit\').click();return false;}  else return true;">';
		while ($arrFetch = mysql_fetch_assoc($resResult))
		{
			#$arrFetch = mysql_fetch_assoc($resResult);
			#echo "<pre>"; print_r($arrFetch); echo "</pre><br />";
			#echo '<option value="'.$arrFetch['id'].'">'.$arrFetch['section'].'-'.$arrFetch['page'].'-'.$arrFetch['article'].'.html</option>';
			/*
			# old style removed 20110511
			$arrContent['content'] .= '<option value="'.$arrFetch['id'].'">'.$arrFetch['section'];
			if ($arrFetch['page'] != "" || $arrFetch['page'] != null)
				$arrContent['content'] .= '-'.$arrFetch['page'];
			if ($arrFetch['article'] != "" || $arrFetch['article'] != null)
				$arrContent['content'] .= '-'.$arrFetch['article'];
			$arrContent['content'] .= '.html</option>';
			*/
			$arrContent['content'] .= '<option value="'.$arrFetch['id'].'">'.$arrFetch['url'].'</option>';
			
		}
		$arrContent['content'] .= '</select>';
		// how do I get the data into setArrRequest. shit this won't work with options.
		#$refFormSelect->setArrRequest($arrFetch);
		#$refFormSelect->processInputData();
		// display the new template YAY!
		#echo $refFormSelect->strTemplate;
	}
	mysql_free_result($resResult);
	// hmm perhaps this should display list of what to edit.
	#echo "ERROR";
	#echo $refForm->strTemplateOrig;
	$strTemplateFile = './content_edit_select.tpl.html';
	$refTPL->go($strTemplateFile, $arrContent);
	echo $refTPL->get_content();
}
#echo "<pre>"; print_r($refForm->arrFields); echo "</pre>";
