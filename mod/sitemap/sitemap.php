<?php
################################################################################
# File Name : sitemap_xml.php                                                  #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Version : 2011070100                                                         #
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
#   This file is used to generate an xml sitemap for this template system      #
#                                                                              #
################################################################################
header('Content-type: text/xml'); // set xml file header
#$strContent = null;
#$refDBC->sql_connect();
// query to grab all pages for sitemap
/*
$strQuery = '
SELECT
	url, pagetitle, changefreq, priority, dateadded, datemod
FROM
	page
WHERE
	boolSitemapHide != "1"
ORDER BY
	url ASC,
	pagetitle ASC
';
*/
// this returns an empty set, we should run 
$strQuery = '
SELECT page.url, page.pagetitle, mod_sitemap.changefreq, mod_sitemap.priority, page.dateadded, page.datemod
FROM page
LEFT JOIN mod_sitemap
	ON page.id = mod_sitemap.pid
WHERE mod_sitemap.boolSitemapHide != "1"
ORDER BY
	page.url ASC,
	page.pagetitle ASC
';
$strQuery = '
SELECT id, url, pagetitle, dateadded, datemod
FROM page
ORDER BY
	url ASC,
	pagetitle ASC
';
#echo $strQuery;
$resResult = $refDBC->query($strQuery); // run query
$intNumRows = mysql_num_rows($resResult); // store rows count
// make sure we get data from the database.
if ($intNumRows > 0)
{
	#$strSection = null;
	#$intCount = 0;
	echo '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
';
	while ($arrFetchSM = mysql_fetch_assoc($resResult))
	{
		$strQuery = '
SELECT changefreq, priority, boolSitemapHide
FROM mod_sitemap
WHERE pid = "'.$arrFetchSM.'"
';
		$resResult2 = $refDBC->query($strQuery);
		$intNumRows = mysql_num_rows($resResult2);
		if ($intNumRows > 0)
		{
			$arrFetchSM2 = mysql_fetch_assoc($resResult2);
		}
		else
		{
			$arrFetchSM2['changefreq'] = 'never';
			$arrFetchSM2['priority'] = '0.5';
			// changefreq
			// priority
		}
		// make sure we don't add section-xml.html
		// this should now be handled by the boolean flag (boolSitemapHide) in the
		// main system.
		#if ($arrFetchSM['section'] != "sitemap" && $arrFetchSM['page'] != "xml")
		#{
			#$intCount++;
			// create the link
			/*
			$strLink = $arrFetchSM['section'];
			if ($arrFetchSM['page'] != null)
				$strLink .= '-'.$arrFetchSM['page'];
			if ($arrFetchSM['article'] != null)
				$strLink .= '-'.$arrFetchSM['article'];
			$strLink .='.html';
			*/
			$strLink = $arrFetchSM['url'];
			// get subfolder data or /
			$intEnd = strrpos($_SERVER['PHP_SELF'], "/");
			$intLength = ++$intEnd;
			$strPHP_SELFFolder = substr($_SERVER['PHP_SELF'], 0, $intEnd);
			// write url tags
			echo '	<url>
		<loc>http://'.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.$strLink.'</loc>
		<lastmod>'.$arrFetchSM['datemod'].'</lastmod>
		<changefreq>'.$arrFetchSM2['changefreq'].'</changefreq>
		<priority>'.$arrFetchSM2['priority'].'</priority>
	</url>
';
		#} // end if
	} // end while
	echo '</urlset>';
}
#$refDBC->sql_close();
?>