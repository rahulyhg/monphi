<?php
################################################################################
# File Name : modules.php                                                      #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2011070700                                                         #
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
################################################################################
# Reference Variables                                                          #
################################################################################
#$refForm = new form_process($arrFormConf);
$refDBC = new dbc($arrDBCAdmin);
$refTPL = new tpl();
################################################################################
#                                                                              #
################################################################################
$arrContent['admin'] = $strAdmin;
$arrContent['modules'] = '';

$refDBC->sql_connect();
################################################################################
#                                                                              #
################################################################################
if (array_key_exists('submit', $_POST))
{
	// disable all modules
	$strQuery = '
UPDATE modules
SET boolEnable = 0
';
	#echo $strQuery."<br />\n";
	$refDBC->query($strQuery);

	// enable modules that are checked.
	$intCount = 0;
	$strWhere = '';
	if (array_key_exists('enable', $_POST))
	{
		foreach ($_POST['enable'] as $strValue)
		{
			if ($intCount > 0)
				$strWhere .= ' || ';
			$strWhere .= 'id="'.mysql_real_escape_string($strValue).'"';
			$intCount++;
		}
		if ($intCount != 0)
		{
			$strQuery = '
UPDATE modules
SET boolEnable = 1
WHERE '.$strWhere.'
';
			#echo $strQuery."<br />\n";
			$refDBC->query($strQuery);
		}
		#echo '<br /><br /><pre>'; print_r($_POST); echo '</pre>';
	}
}
################################################################################
#                                                                              #
################################################################################


/*
$strQuery = '
SELECT id, moduleName, fileName
FROM module
ORDER BY moduleName
';
*/
$strQuery = '
SELECT id, name, path, description, boolEnable
FROM modules
ORDER BY name
';

$resResult = $refDBC->query($strQuery);
$intNumRows = mysql_num_rows($resResult);
$intCount = 0;
if ($intNumRows != 0)
{
	$arrContent['users'] = '';
	while ($arrFetch = mysql_fetch_assoc($resResult))
	{
		$intCount ++;
		if ($intCount % 2 == 1)
			$strClass="";
		else
			$strClass=' class="list2"';

		if ($arrFetch['boolEnable'])
			$strCheckbox = '<input class="check" name="enable[]" type="checkbox" value="'.$arrFetch['id'].'" checked />';
		else
			$strCheckbox = '<input class="check" name="enable[]" type="checkbox" value="'.$arrFetch['id'].'" />';
//style="vertical-align:middle;padding-right:5px;"
		$arrContent['modules'] .= '
		<tr>
			<td'.$strClass.'>'.$strCheckbox.'</td>
			<td'.$strClass.'>'.$arrFetch['name'].'</td>
			<td'.$strClass.'>'.$arrFetch['path'].'</td>
			<td'.$strClass.'>'.$arrFetch['description'].'</td>
		</tr>
';
//			<td'.$strClass.'><a href="module_edit.php?id='.$arrFetch['id'].'" title="Edit : '.$arrFetch['name'].'">edit</a>, <a href="module_delete.php?id='.$arrFetch['id'].'" title="Delete : '.$arrFetch['name'].'">delete</a></td>
	}
}
$refDBC->sql_close();

$arrContent['count'] = "Total Modules: ".$intCount;

$strTemplateFile = 'modules.tpl.html';
$refTPL->go($strTemplateFile, $arrContent);
echo $refTPL->get_content();
?>