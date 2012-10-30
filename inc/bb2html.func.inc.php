<?php
########################################################################
# File Name : bb2html.func.inc.php                                     #
# Author(s) :                                                          #
#   author: Louai Munajim                                              #
#   website: http://elouai.com                                         #
#   Phil Allen phil@hilands.com                                        #
# Last Edited By :                                                     #
#   phil@hilands.com                                                   #
# Version : 2007122700                                                 #
#                                                                      #
# Copyright :                                                          #
#   This file is a php form input test script                          #
#   Copyright (C) 2005 Philip J Allen                                  #
#                                                                      #
#   This file is free software; you can redistribute it and/or modify  #
#   it under the terms of the GNU General Public License as published  #
#   by the Free Software Foundation; either version 2 of the License,  #
#   or (at your option) any later version.                             #
#                                                                      #
#   This File is distributed in the hope that it will be useful,       #
#   but WITHOUT ANY WARRANTY; without even the implied warranty of     #
#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      #
#   GNU General Public License for more details.                       #
#                                                                      #
#   You should have received a copy of the GNU General Public License  #
#   along with This File; if not, write to the Free Software           #
#   Foundation, Inc., 51 Franklin St, Fifth Floor,                     #
#   Boston, MA  02110-1301  USA                                        #
#                                                                      #
# External Files:                                                      #
#                                                                      #
# General Information (algorithm) :                                    #
#                                                                      #
# Functions :                                                          #
#                                                                      #
# Classes :                                                            #
#   Currently N/A                                                      #
#                                                                      #
# CSS :                                                                #
#                                                                      #
# JavaScript :                                                         #
#                                                                      #
# Variable Lexicon :                                                   #
#   String             - $strStringName                                #
#   Array              - $arrArrayName                                 #
#   Resource           - $resResourceName                              #
#   Reference Variable - $refReferenceVariableName  (aka object)       #
#   Integer            - $intIntegerName                               #
#   Boolean            - $boolBooleanName                              #
#   Function           - function_name (all lowercase _ as space)      #
#   Class              - class_name (all lowercase _ as space)         #
#                                                                      #
# Commenting Style :                                                   #
#   # (in boxes) denotes commenting for large blocks of code, function #
#       and classes                                                    #
#   # (single at beginning of line) denotes debugging infromation      #
#       like printing out array data to see if data has properly been  #
#       entered                                                        #
#   # (single indented) denotes commented code that may later serve    #
#       some type of purpose                                           #
#   // used for simple notes inside of code for easy follow capability #
#   /* */ is only used to comment out mass lines of code, if we follow #
#       the above way of code we will be able to comment out entire    #
#       files for major debugging                                      #
#                                                                      #
########################################################################

// A simple FAST parser to convert BBCode to HTML
// Trade-in more restrictive grammar for speed and simplicty
//
// Syntax Sample:
// --------------
// [img]http://elouai.com/images/star.gif[/img]
// [url="http://elouai.com"]eLouai[/url]
// [mail="webmaster@elouai.com"]Webmaster[/mail]
// [size="25"]HUGE[/size]
// [color="red"]RED[/color]
// [b]bold[/b]
// [i]italic[/i]
// [u]underline[/u]
// [list][*]item[*]item[*]item[/list]
// [code]value="123";[/code]
// [quote]John said yadda yadda yadda[/quote]
//
// Usage:
// ------
/* <?php include 'bb2html.php'; ?>
// <?php $htmltext = bb2html($bbtext); ?>
*/
//
// (please do not remove credit)
// author: Louai Munajim
// website: http://elouai.com
// date: 2004/Apr/18


function bb2html($text)
{
	$bbcode = array(
		"<", ">",
		"[b]", "[/b]", 
		"[i]", "[/i]",
		"[u]", "[/u]", 
		"[left]", "[/left]",
		"[center]", "[/center]",
		"[right]", "[/right]",
		"[code]", "[/code]",
		"[quote]", "[/quote]",
		"[list]", "[*]", "[/list]", 
		"[img]", "[/img]", 
		'[color="', "[/color]",
		"[size=\"", "[/size]",
		'[url="', "[/url]",
		"[mail=\"", "[/mail]",
		'"]');
	$htmlcode = array(
		"&lt;", "&gt;",
		"<b>", "</b>", 
		"<i>", "</i>",
		"<u>", "</u>", 
		"<div style=\"text-align:left;\">", "</div>",
		"<div style=\"text-align:center;\">", "</div>",
		"<div style=\"text-align:right;\">", "</div>",
		"<div style=\"border:2px #ccc solid; width:100%; padding:2px; background-color:#fff; color:#333;\">", "</div>",
		"<div style=\"border-left:5px #333 solid; border-right:1px #333 dotted; border-top:1px #333 dotted; border-bottom:1px #333 dotted;width:100%; padding:2px; font-style:italic;\"><span style=\"font-size:18px; font-weight:bold; font-family:'Times New Roman', 'Times';\">&#8220;</span>", "<span style=\"font-size:18px;font-weight:bold; font-family:'Times New Roman', 'Times';\">&#8221;</span></div>",
		"<ul>", "<li>", "</ul>", 
		"<img src=\"", "\">", 
		"<span style=\"color:", "</span>",
		"<span style=\"font-size:", "</span>",
		'<a href="', "</a>",
		"<a href=\"mailto:", "</a>",
		'">');
	$newtext = str_replace($bbcode, $htmlcode, $text);
	//nl2br replaces carriage returns \n new lines with <br />
	$newtext = nl2br($newtext);//second pass
	return $newtext;
}
?>
