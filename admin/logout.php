<?php
################################################################################
# File Name : logout.php                                                       #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2011062000                                                         #
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
################################################################################
# Grab Admin Menu                                                              #
################################################################################
//this is crude
include ("admin_menu.tpl.html");
session_start();
#echo "<pre>"; print_r($_SESSION); echo "</pre><br />\n";
$_SESSION['monphi_auth'] = false;
$_SESSION['mohphi_auth_level'] = null;
$_SESSION['mohphi_auth_user'] = null;
$_SESSION['mohphi_auth_keepalive'] = null;
$_SESSION['mohphi_auth_ip_addr'] = null;
$_SESSION['mohphi_auth_user_agent'] = null;
$_SESSION['mohphi_auth_random_seed'] = null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<title>
		The Monphi Engine - Administration Menu
	</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<style type="text/css" media="screen">
		@import url("../css/monphi-admin.css");
		@import url("../css/monphi.css");
	</style>
</head>

<body>
<!-- Form here -->
<center>
<div class="content">
	<div style="text-align:center;">
		<a href="index.php"><img src="../img/monphi/banner.jpg" alt="Monphi" title="monphi" style="border:1px #ccc solid; border-bottom: 2px solid #333; -webkit-border-radius: 5px; -moz-border-radius: 5px;" /></a><br />
	</div>
	<br />
	<div style="text-align:center;">
		You have successfully logged out
	</div>
</div>
</body>
</html>