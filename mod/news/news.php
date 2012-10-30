<?php
################################################################################
# File Name : news.php                                                         #
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
$strNewsTemplateFile = 'mod/news/news.tpl.html';
$strNewsContent = '';
$refNewsTPL = new tpl();
//$strContent = null;
#$refDBC->sql_connect();
/*
$strQuery = '
SELECT *
FROM page
WHERE boolDisplayNews = 1
ORDER BY
	dateadded DESC,
	url DESC
';
*/
$strQuery = '
SELECT page.dateadded, page.url, page.pagetitle, mod_news.contentNews, mod_news.boolNewsPagetitle, mod_news.boolNewsContent, mod_news.boolNewsMore
FROM mod_news
LEFT JOIN page
	on page.id = mod_news.pid
WHERE mod_news.boolDisplayNews = 1
ORDER BY
	page.dateadded DESC,
	page.pagetitle ASC
';
$i = 0;
$resResult = $refDBC->query($strQuery);
$intNumRows = mysql_num_rows($resResult);
//echo '<div style="text-align:justify">';
//$strContent .= '<div style="text-align:justify">';
if ($intNumRows > 0)
{
	while ($arrFetch2 = mysql_fetch_assoc($resResult))
	{
		#echo '<pre>';print_r($arrFetch2);echo '</pre>';
		$i++;
		$arrNewsContent['pagetitle'] = '';
		$arrNewsContent['contentNews'] = '';
		$arrNewsContent['readMore'] = '';
		if ($arrFetch2['boolNewsPagetitle'])
		{
			//$strContent .= '<b>'.$arrFetch2['pagetitle'].'</b><br />';
			$arrNewsContent['pagetitle'] = $arrFetch2['pagetitle'];
		}
		// check which section to pull content from.
		if ($arrFetch2['boolNewsContent'])
		{
			//$strContent .= $arrFetch2['contentNews'];
			$arrNewsContent['contentNews'] = $arrFetch2['contentNews'];
			if ($arrFetch2['boolNewsMore'])
			{
				// add to strContent
				$strURL = $arrFetch2['url'];
				/*
				$strURL .= $arrFetch2['section'];
				if ($arrFetch2['page'] != "")
				{
					$strURL .= "-".$arrFetch2['page'];
				}
				if ($arrFetch2['article'] != "")
				{
					$strURL .= "-".$arrFetch2['article'];
				}
				$strURL .= ".html";
				*/
				//$strContent .= '<br /><a href="'.$strURL.'"><i>Read More ...</i></a>';
				$arrNewsContent['readMore'] = '<br /><a href="'.$strURL.'"><i>Read More ...</i></a>';
			}
		}
		else
		{
			// need to run a check vs includes
			//$strContent = $arrFetch2['content'];
			// this currently isn't in the news content module...
			$arrNewsContent['contentNews'] = $arrFetch2['content'];
			if ($arrFetch2['boolBBCode'])
				//$strContent = bb2html($strContent);
				$arrNewsContent['contentNews'] = bb2html($arrFetch2['content']);
		}
/*		echo '
		'.$strContent.'
		<br />
		<!--<span style="font-style:italic;">Lyconis - '.$arrFetch2['dateadded'].'</span>-->';
*/
		$refNewsTPL->go($strNewsTemplateFile, $arrNewsContent);
		$strNewsContent .= $refNewsTPL->get_content();
		$refNewsTPL->set_reset();
	}
}
else
{
	echo ' There are no news articles available';
}
//$strContent .= '</div>';
//$strContent .= '<hr />';
//echo $strContent;
#$refDBC->sql_close();
#echo $i;
echo $strNewsContent;

?>