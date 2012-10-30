<?php
########################################################################
# DB arrays                                                            #
#   for use with database connectivity class                           #
#   host - host name or IP address to connect to                       #
#   user - user name to authenticate to database with                  #
#   pass - password to authenticate to database with                   #
#   db - database to connect to, set to null or ''                     #
########################################################################
$arrDBC = array(
	'host' => 'localhost',
	'user' => 'dbuserhere', // host
	'pass' => 'dbpasshere', //host
	'db'   => 'dbnamehere', //host // if you don't connect to a database set to null or ''
	'type' => 'mysql',
	'conn' => 0, // auto connect 0 false 1 true
	'error' => 'die', // die or string
	'wraperror' => array(
		'0' => "\n".'<div style="text-align:center; font-size:1.5em; font-weight:bold; color:#c00;">Error</div><div style="color:#c00; border:1px #c00 solid; padding:5px; text-align:center;">',
		'1' => "</div>\n"
		) // wrap error messages with this 0 start 1 end
	);
$arrDBCAdmin = array(
	'host' => 'localhost',
	'user' => 'dbuserhere2', // host
	'pass' => 'dbpasshere2', //host
	'db'   => 'dbnamehere', //host // if you don't connect to a database set to null or ''
	'type' => 'mysql',
	'conn' => 0, // auto connect 0 false 1 true
	'error' => 'die', // die or string
	'wraperror' => array(
		'0' => "\n".'<div style="text-align:center; font-size:1.5em; font-weight:bold; color:#c00;">Error</div><div style="color:#c00; border:1px #c00 solid; padding:5px; text-align:center;">',
		'1' => "</div>\n"
		) // wrap error messages with this 0 start 1 end
	);

?>