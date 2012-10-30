<?php
################################################################################
# File Name : news-archive.php                                                 #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2011070200                                                         #
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
# need to add boolNewsArchived.

$strContent = null;
#$refDBC->sql_connect();
//	section, page, article, pagetitle, dateadded, datemod

/*
$strQuery = '
SELECT url, pagetitle, dateadded, datemod
FROM page
WHERE boolArchiveNews = 1
ORDER BY
	dateadded DESC,
	pagetitle ASC
';
*/
$strQuery = '
SELECT page.url, page.pagetitle, page.dateadded, page.datemod
FROM page
LEFT JOIN mod_news
	on page.id = mod_news.pid
WHERE mod_news.boolArchiveNews = 1
ORDER BY
	page.dateadded DESC,
	page.pagetitle ASC
';

#	boolDisplayNews = 1
#	boolArchiveNews = 1
/*
news
SELECT
	section, pagetitle
FROM
	page
WHERE
	boolDisplayNews = 1
ORDER BY
	section ASC,
	pagetitle ASC
*/
$i = 0;
$resResult = $refDBC->query($strQuery);
$intNumRows = mysql_num_rows($resResult);
echo '<div style="text-align:justify">';
if ($intNumRows > 0)
{
	// flag string for group sections
	$strSection = null;
	$intCount = 0;
	while ($arrFetchSM = mysql_fetch_assoc($resResult))
	{
		$boolCloseDiv = false;
		// create the link
/*
		$strLink = $arrFetchSM['section'];
		if ($arrFetchSM['page'] != null)
			$strLink .= '-'.$arrFetchSM['page'];
		if ($arrFetchSM['article'] != null)
			$strLink .= '-'.$arrFetchSM['article'];
		$strLink .='.html';
		echo '<a href="'.$strLink.'">'.$arrFetchSM['pagetitle'].'</a>&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:8px; font-style:italic;">Added - '.$arrFetchSM['dateadded'].'</span><br />';
*/
		echo '<a href="'.$arrFetchSM['url'].'">'.$arrFetchSM['pagetitle'].'</a>&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:.75em; font-style:italic;">Added - '.$arrFetchSM['dateadded'].'</span><br />';
	}
}
else
{
	echo ' There are no news articles in the archives';
}

echo '</div>';
#$refDBC->sql_close();
?>