<?php
################################################################################
# File Name : submenu.inc.php                                                  #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2011012600                                                         #
# General Information (algorithm) :                                            #
#   This file contains the dynamic navigation menu for hilands.com             #
################################################################################
// this is more of a stub holder to empty it out.. We'll do something similar to the menu.inc for a temporary hack until we can add it into the main system.
$strModSubMenu = '';
#echo "<br /><br />"; print_r($arrPage);
#echo "<br /><br />".$strURL;
#if ($arrPage['section'] == 'security')
if (substr($strURL, 0, 8) == 'security')
{
	$strModSubMenu = '		<div id="sub_menu">
			<span class="bold">
				<a href="./security.html">Attack Vectors</a> <span class="barsep">::</span> <a href="./security-windows.html">Windows Docs</a> <span class="barsep">::</span> <a href="security-tools.html">Tools</a> <span class="barsep">::</span> <a href="#">References</a>
			</span>
		</div>
';
}

#elseif ($arrPage['section'] == 'code')
elseif (substr($strURL, 0, 4) == 'code')
{
	$strModSubMenu = '		<div id="sub_menu" style="font-weight:bold;">
			<a href="code-html.html">HTML</a> <span class="barsep">::</span>
			<a href="code-php.html">PHP</a> <span class="barsep">::</span>
			<!--<a href="code-perl.html">Perl</a> <span class="barsep">::</span>-->
			<a href="code-c.html">C / C++</a> <span class="barsep">::</span>
			<a href="code-shell.html">Shell</a> <span class="barsep">::</span>
			<!--<a href="code-java.html">Java</a>-->
			<a href="code-vbs.html">VBS</a>
		</div>
';
}
#elseif ($arrPage['section'] == 'os')
elseif (substr($strURL, 0, 2) == 'os')
{
	$strModSubMenu = '		<div id="sub_menu">
			<span class="bold">
				<a href="os-linux.html">Linux</a> <span class="barsep">::</span>
				<a href="os-windows.html">Windows</a> <span class="barsep">::</span>
				<a href="os-mac.html">Macintosh</a>
			</span>
		</div>
';
}
echo $strModSubMenu;
?>