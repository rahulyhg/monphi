<?php
################################################################################
# File Name : menu.inc.php                                                     #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2012102100                                                         #
# General Information (algorithm) :                                            #
#   This file contains the dynamic navigation menu for hilands.com             #
################################################################################
$strModNavMenu = '';
#if ($arrPage['section'] == 'index')
if ($strURL == '' || $strURL == 'index.html')
	$strModNavMenu .= '			<span class="bold">Home</span><span class="barsep">::</span>'."\n";
	else
	$strModNavMenu .= '			<a class="menu bold" href="./">Home</a><span class="barsep">::</span>'."\n";
#if ($arrPage['section'] == 'code' && $arrPage['page'] == null)
if ($strURL == 'code.html')
	$strModNavMenu .= '			<span class ="bold">Code</span><span class="barsep">::</span>'."\n";
	else
	$strModNavMenu .= '			<a class="menu bold" href="./code.html">Code</a><span class="barsep">::</span>'."\n";
#if ($arrPage['section'] == 'links' && $arrPage['page'] == null)
if ($strURL == 'links.html')
	$strModNavMenu .= '			<span class ="bold">Links</span><span class="barsep">::</span>'."\n";
	else
	$strModNavMenu .= '			<a class="menu bold" href="./links.html">Links</a><span class="barsep">::</span>'."\n";
#if ($arrPage['section'] == 'network' && $arrPage['page'] == null)
#if (substr($strURL, 0, 7) == 'network')
if ($strURL == 'network')
	$strModNavMenu .= '			<span class ="bold">Network</span><span class="barsep">::</span>'."\n";
	else
	$strModNavMenu .= '			<a class="menu bold" href="./network.html">Network</a><span class="barsep">::</span>'."\n";

#if ($arrPage['section'] == 'os' && $arrPage['page'] == null)
if ($strURL == 'os.html')
	$strModNavMenu .= '			<span class ="bold">Operating Systems</span><span class="barsep">::</span>'."\n";
	else
	$strModNavMenu .= '			<a class="menu bold" href="./os.html">Operating Systems</a><span class="barsep">::</span>'."\n";
#if ($arrPage['section'] == 'security' && $arrPage['page'] == null)
if ($strURL == 'security.html')
	$strModNavMenu .= '			<span class ="bold">Security Docs</span><span class="barsep">::</span>'."\n";
	else
	$strModNavMenu .= '			<a class="menu bold" href="./security.html">Security Docs</a><span class="barsep">::</span>'."\n";
#if ($arrPage['section'] == 'software' && $arrPage['page'] == null)
if ($strURL == 'software.html')
	$strModNavMenu .= '			<span class ="bold">Software</span><span class="barsep">::</span>'."\n";
	else
	$strModNavMenu .= '			<a class="menu bold" href="./software.html">Software</a><span class="barsep">::</span>'."\n";
#if ($arrPage['section'] == 'statistics' && $arrPage['page'] == null)
#	$strModNavMenu .= '			<span class ="bold">Statistics</span><span class="barsep">::</span>'."\n";
#	else
#	$strModNavMenu .= '			<a class="menu bold" href="./statistics.html">Statistics</a><span class="barsep">::</span>'."\n";
#if ($arrPage['section'] == 'sysadmin' && $arrPage['page'] == null)
if ($strURL == 'sysadmin.html')
	$strModNavMenu .= '			<span class ="bold">Sys Admin</span>'."\n";
	else
	$strModNavMenu .= '			<a class="menu bold" href="./sysadmin.html">Sys Admin</a>'."\n";
#if ($arrPage['section'] == 'tutorials' && $arrPage['page'] == null)
#	$strModNavMenu .= '			<span class ="bold">Tutorials</span>'."\n";
#	else
#	$strModNavMenu .= '			<a class="menu bold" href="./tutorials.html">Tutorials</a>'."\n";
echo $strModNavMenu;
?>
