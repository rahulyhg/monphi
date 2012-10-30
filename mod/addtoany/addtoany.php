<?php
################################################################################
# File Name : addtoany.php                                                     #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2010081100                                                         #
#                                                                              #
# Copyright :                                                                  #
#   Copyright 2005, 2006, 2007, 2008, 2009, 2010 Philip Allen                  #
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
// get the monphi engine page name.
/*$strURL .= $arrPage['section'];
if ($arrPage['page'] != "" || $arrPage['page'] != null)
	$strURL .= '-'.$arrPage['page'];
if ($arrPage['article'] != "" || $arrPage['article'] != null)
	$strURL .= '-'.$arrPage['article'];
$strURL .= '.html';
*/
#$strURL = preg_replace('/[^A-Za-z0-9-_.\/]/', '', $_SERVER['REQUEST_URI']);
#$strURL = mysql_real_escape_string($strURL);
// grab full http server name;
#$strURL .= 'http://'.$_SERVER['HTTP_HOST'].$strURL;
#echo 'url : '.$strURL."<br />\n";
// replace [url] with $strURL
// [title] will come from $arrContent['title']
if (!empty($_SERVER['HTTPS']))
	$strServer = "https://";
else
	$strServer = "http://";

$refModShareTPL = new tpl();
// strCurrentLocation grabbed from index
$arrModShare['url'] = $strServer.$_SERVER['HTTP_HOST'].'/'.$strCurrentLocation;
#echo $arrModShare['url']."<br />\n";
$arrModShare['title'] = $arrContent['title'];
$refModShareTPL->go("mod/addtoany/addtoany.tpl.html", $arrModShare);
echo $refModShareTPL->get_content();

?>