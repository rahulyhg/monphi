<?php
################################################################################
# File Name : software.php                                                     #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Version : 2012012400                                                         #
#                                                                              #
# Copyright :                                                                  #
#   This file is a php template processing script                              #
#   Copyright (C) 2009 Philip J Allen                                          #
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
#   This file is used to generate an xml sitemap for this template system      #
#                                                                              #
# Variable Lexicon :                                                           #
#   String             - $strStringName                                        #
#   Array              - $arrArrayName                                         #
#   Resource           - $resResourceName                                      #
#   Reference Variable - $refReferenceVariableName  (aka object)               #
#   Integer            - $intIntegerName                                       #
#   Boolean            - $boolBooleanName                                      #
#   Function           - function_name (all lowercase _ as space)              #
#   Class              - class_name (all lowercase _ as space)                 #
#                                                                              #
# Commenting Style :                                                           #
#   # (in boxes) denotes commenting for large blocks of code, function         #
#       and classes                                                            #
#   # (single at beginning of line) denotes debugging infromation              #
#       like printing out array data to see if data has properly been          #
#       entered                                                                #
#   # (single indented) denotes commented code that may later serve            #
#       some type of purpose                                                   #
#   // used for simple notes inside of code for easy follow capability         #
#   /* */ is only used to comment out mass lines of code, if we follow         #
#       the above way of code we will be able to comment out entire            #
#       files for major debugging                                              #
#                                                                              #
################################################################################
// need to add a field for changefreq
$strContent = null;
#$refDBC->sql_connect();

$strQuery = '
SELECT
	*
FROM
	mod_software
ORDER BY
	header ASC
';
#echo $strQuery;
$resResult = $refDBC->query($strQuery);
$intNumRows = mysql_num_rows($resResult);
#echo "NumRows : ".$intNumRows."<br />\n";
if ($intNumRows > 0)
{
	// flag string for group sections
	$strSection = null;
	$intCount = 0;
	while ($arrFetchSoft = mysql_fetch_assoc($resResult))
	{
		$intCount++;
		#echo "intCount : ".$intCount."<br />\n";
if ($intCount > 1)
	echo '<hr />';
echo '<div style="float:left; padding-right:10px;">
';
if ($arrFetchSoft['icon_gui'] == "1")
	echo '	<img src="mod/software/img/mouse2.png" alt="Graphical User Interface (GUI)" title="Graphical User Interface (GUI)"/><br />'."\n";
if ($arrFetchSoft['icon_tech'] == "1")
	echo '	<img src="mod/software/img/nerd-glasses.icon.png" alt="Nerdware software for techs" title="Nerdware software for techs" /><br />'."\n";
if ($arrFetchSoft['icon_cli'] == "1")
	echo '	<img src="mod/software/img/terminal.png" alt="Command Line Interface" title="Command Line Interface" /><br />'."\n";
if ($arrFetchSoft['icon_linux'] == "1")
	echo '	<img src="mod/software/img/tux_penguin.icon.png" alt="Linux" title="Linux" /><br />'."\n";
if ($arrFetchSoft['icon_windows'] == "1")
	echo '	<img src="mod/software/img/windows_logo.icon.png" alt="Windows" title="Windows" /><br />'."\n";
if ($arrFetchSoft['icon_gnu'] == "1")
	echo '	<img src="mod/software/img/baby_gnu_icon.png" alt="GNU Software" title="GNU Software" /><br />'."\n";
if ($arrFetchSoft['icon_easy'] == "1")
	echo '	<img src="mod/software/img/easy.png" alt="Easy to use" title="Easy to use" /><br />'."\n";
if ($arrFetchSoft['icon_cd'] == "1")
	echo '	<img src="mod/software/img/cd.png" alt="Bootable CD-Rom" title="Bootable CD-Rom" /><br />'."\n";

echo '</div>
';
echo '<div style="float:right; padding-left:10px;">
';
if ($arrFetchSoft['imgthumb'] != "")
{
echo '	<a href="'.$arrFetchSoft['img'].'" rel="lightbox" title="'.$arrFetchSoft['header'].'"><img src="'.$arrFetchSoft['imgthumb'].'" alt="'.$arrFetchSoft['header'].'" title="'.$arrFetchSoft['header'].'" /></a>';
/*
echo '	<img src="'.$arrFetchSoft['imgthumb'].'" alt="'.$arrFetchSoft['header'].'" title="'.$arrFetchSoft['header'].'" style="cursor: pointer;" onclick="grayOut(true);display_image_toggle(\''.$arrFetchSoft['imgthumb'].'\');"/>
	<div id="'.$arrFetchSoft['imgthumb'].'" style="display: none; cursor: pointer; position: absolute; text-align:left; float: left;">
		<img src="img/close.png" alt="close" title="close" onclick="display_image_toggle(\''.$arrFetchSoft['imgthumb'].'\');grayOut(false);" style="position:relative; left:-11px; top:+15px;" />
		<div onclick="display_image_toggle(\''.$arrFetchSoft['imgthumb'].'\');grayOut(false);" style="padding:10px; text-align:center;border:3px #333 solid; background-color:#fff;">
			<img src="'.$arrFetchSoft['img'].'" alt="'.$arrFetchSoft['header'].'" title="'.$arrFetchSoft['header'].'" />
		</div>
		<div style="color:#fff; font-weight:bold; text-align:center;">
			'.$arrFetchSoft['header'].'
		</div>
	</div>
';
*/
/*
	echo '	<a href="JavaScript:winpop2(\''.$arrFetchSoft['img'].';popup;778;597\')">
		<img src="'.$arrFetchSoft['imgthumb'].'" alt="imgthumb" />
	</a>
';
*/
}
echo '</div>
<div>
	<b>'.$arrFetchSoft['header'].'</b><br />
	'.$arrFetchSoft['description'].'
	<br /><br />
	Website - <a href="'.$arrFetchSoft['urlwebsite'].'">'.$arrFetchSoft['urlwebsite'].'</a><br />';
if ($arrFetchSoft['urldownload'] != "")
{
	echo '	<a href="'.$arrFetchSoft['urldownload'].'" style="text-decoration:none;"><img src="mod/software/img/floppydisk.png" alt="download" style="vertical-align:middle;" /></a>
	<a href="'.$arrFetchSoft['urldownload'].'">Download</a> <i>'.$arrFetchSoft['filesize'].' bytes</i><br />
	<i>md5 - '.$arrFetchSoft['md5'].' *'.$arrFetchSoft['filename'].'</i><br />
';
}
echo '</div>
<div style="clear:both;"></div>
';
	}
}
#$refDBC->sql_close();
?>
