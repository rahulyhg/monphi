<?php
################################################################################
# File Name : user.php                                                         #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2011053000                                                         #
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
$refDBC->sql_connect();

$strQuery = '
SELECT *
FROM users
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
		if ($arrFetch['active'] == 1)
			$strStatus = 'active';
		else
			$strStatus = 'inactive';
		$arrContent['users'] .= '
		<tr>
			<td'.$strClass.'>'.$arrFetch['user'].'</td>
			<td'.$strClass.'>'.$strStatus.'</td>
			<td'.$strClass.'>'.$arrFetch['last_login'].'</td>
			<td'.$strClass.'><a href="user_edit.php?uid='.$arrFetch['uid'].'" title="Edit : '.$arrFetch['user'].'">edit</a>, <a href="user_delete.php?uid='.$arrFetch['uid'].'" title="Delete : '.$arrFetch['user'].'" onClick="return confirmSubmit()">delete</a></td>
		</tr>
';
	}
}
$refDBC->sql_close();

$strTemplateFile = 'user.tpl.html';
$refTPL->go($strTemplateFile, $arrContent);
echo $refTPL->get_content();
?>