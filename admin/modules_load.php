<?php
################################################################################
# File Name : modules_load.php                                                 #
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
	// enable modules that are checked.
	if (array_key_exists('load', $_POST))
	{
		foreach ($_POST['load'] as $strDir)
		{
			#echo '<br /><br />'.print_r($_POST['load']);
			#echo $strDir;
			if (is_file('../mod/'.$strDir.'/'.$strDir.'.info.php'))
			{
				include ('../mod/'.$strDir.'/'.$strDir.'.info.php');
				#echo '<pre>'.$moduleInsert.'</pre>';
				if (isset($moduleInsert))
				{
					$refDBC->query('alter table modules AUTO_INCREMENT = 0');
					$refDBC->query('alter table module_blocks AUTO_INCREMENT = 0');
					#echo '<pre>'.$moduleInsert.'</pre><br />';
					$refDBC->query($moduleInsert);
					$intMid = mysql_insert_id(); //like last_insert_id mysql.  not good for bigint (64 bits)
					#echo '<pre>'.$blocksInsert.'</pre>';
					// if module is capable of blocks insert blocks info here.
					if (isset($blocksInsert))
					{
						$blocksInsert = str_replace("[mid]", $intMid, $blocksInsert);
						#echo '<pre>'.$blocksInsert.'</pre><br />';
						$refDBC->query($blocksInsert);
					}
					// if module requires a seperate data table add it here.
					if (isset($moduleTable))
					{
						$refDBC->query($moduleTable); // adds module table.
						// if we have an insert for the table run it here. pulls
						// moduleTableInsert and moduleTableValue
						if (isset($moduleTableInsert))
						{
$strQuery = "
SELECT id
FROM page
";
							$refDBC->query($strQuery);
							$resResult = $refDBC->query($strQuery);
							$intNumRows = mysql_num_rows($resResult);
							if ($intNumRows != 0)
							{
								$intCount = 0;
								$moduleTableValues = '';
								while ($arrFetch = mysql_fetch_assoc($resResult))
								{
									$intCount++;
									if ($intCount > 1)
									$moduleTableValues .= ', ';
									$moduleTableValues .= str_replace("[pid]", $arrFetch['id'], $moduleTableValue);
									
								}
								$strQuery = $moduleTableInsert.$moduleTableValues;
								#echo '<pre>'.$strQuery.'</pre><br />';
								$refDBC->query($strQuery);
							}
						}
					} //end moduleTable
				}
				else
				{
					// error no installation query found
					echo '<br /><br />ERROR - installation query not found in ../mod/'.$strDir.'/'.$strDir.'.info.php';
				}
			}
		}
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

/*
$strQuery = '
SELECT id, name, path, description, boolEnable
FROM modules
ORDER BY name
';

$resResult = $refDBC->query($strQuery);
$intNumRows = mysql_num_rows($resResult);
if ($intNumRows != 0)
{
	$intCount = 0;
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
*/
$arrFiles = scandir('../mod/');
#echo '<br /><br /><pre>';print_r($arrFiles);echo '</pre>';
foreach($arrFiles as $strValue)
{
	if (is_dir('../mod/'.$strValue))
	{
	#	echo $strValue.' is a directory<br />';
		if ($strValue != '.' && $strValue != '..')
		{
			$arrDirs[$strValue] = $strValue;
		}
	}
	#else
	#	echo $strValue.' is not a directory<br />';

}

#echo '<br /><br /><pre>';print_r($arrDirs);echo '</pre>';




$strQuery = '
SELECT path
FROM modules
';
$resResult = $refDBC->query($strQuery);
$intNumRows = mysql_num_rows($resResult);
if ($intNumRows != 0)
{
	while ($arrFetch = mysql_fetch_assoc($resResult))
	{
		// unset
		$strDir = substr($arrFetch['path'], 4, -1);
		#echo $strDir;
		#print_r($arrFetch);
		unset($arrDirs[$strDir]);
	}
}

#echo '<br /><br /><pre>';print_r($arrDirs);echo '</pre>';
	$intCount = 0;

foreach ($arrDirs as $strDir)
{
	if (is_file('../mod/'.$strDir.'/'.$strDir.'.info.php'))
	{
		include ('../mod/'.$strDir.'/'.$strDir.'.info.php');
	// read info file.
		$intCount ++;
		if ($intCount % 2 == 1)
			$strClass="";
		else
			$strClass=' class="list2"';
	$strCheckbox = '<input class="check" name="load[]" type="checkbox" value="'.$strDir.'" />';
//style="vertical-align:middle;padding-right:5px;"
		$arrContent['modules'] .= '
		<tr>
			<td'.$strClass.'>'.$strCheckbox.'</td>
			<td'.$strClass.'>'.$name.'</td>
			<td'.$strClass.'>'.$description.'</td>
		</tr>';
	}
}

#$arrContent['count'] = "Total Modules Found: ".count($arrDirs); // this finds all dirs not just ones with the php file we want.
$arrContent['count'] = "Total Modules Found: ".$intCount;



$refDBC->sql_close();

$strTemplateFile = 'modules_load.tpl.html';
$refTPL->go($strTemplateFile, $arrContent);
echo $refTPL->get_content();
?>