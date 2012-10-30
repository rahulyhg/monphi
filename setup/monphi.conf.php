<?php
#$strTemplateFile = './tpl/main.tpl.html';
$strTemplateFile = './main.tpl.html';
$arrwraperror = array(
	'0' => "<br />\n".'<div style="background-color:#ffffd5; border:3px #c00 solid; color:#c00; padding:5px;">'."\n\t".'<div style="font-size:1.5em; font-weight:bold; padding-bottom:.5em;"><img src="img/monphi/logo.png" style="vertical-align:middle;" /> Uh oh, there seems to be a problem!</div>',
	'1' => "</div>\n"
	);
//----------------------------------------------------------------------------//
// Settings for Authentication                                                //
//----------------------------------------------------------------------------//
$strRandomSeed = '[randomseed]'; // should be unique per instance.
$intKeepAlive = '[keepalive]'; //time in seconds default 600 seconds or 10 minutes
$boolUseToken = [usetoken]; // Use tokens for authed users, security for hijacking.
$boolUseHTTPSCookies = [usehttpscookies]; // This should be set to true for added security. Must have HTTPS enabled. Will throw "Invalid Token or Token Timeout Reached" error if unable to send via https
$boolCheckIPAddress = [checkipaddress]; // Validates authed user is coming from same IP, may cause problems with certain ISPs or Anonymizer systems.
$boolCheckUserAgent = [checkuseragent]; // Validates authed user is coming from same web client, may cause problems with certain browsers.
?>