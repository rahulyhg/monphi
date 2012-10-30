<?php
########################################################################
# File Name : db.inc.php                                               #
# Author(s) :                                                          #
#   Phil Allen - phil@hilands.com                                      #
# Last Edited By :                                                     #
#   phil@hilands.com                                                   #
# Version : 2009112100                                                 #
# ChangeLog :                                                          #
#   20091121 - fixed the wrap_error function, changed the end error    #
#   to use the secondary array 1 instead of 0.                         #
#                                                                      #
# Copyright :                                                          #
#   Database Include                                                   #
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
#   List of External Files all require/includes                        #
#                                                                      #
# General Information (algorithm) :                                    #
#   Tested to work with PHP 5                                          #
#   Database Connectivity, queries and other oddity functions          #
#   supports connectivity to mssql and mysql one database per          #
#   object/reference variable created. give connection, close          #
#   some accessors to get arrdbc data for object/reference variable    #
#   along with some basic queries, select all queries andselect all    #
#   where queries and of course mysql auto increment filler            #
#   Near Full Error Checking built in. Uses CSS for error messages     #
#   most errors result in termination of script via die()              #
#   *mssql* freetds is required                                        #
#                                                                      #
# Functions :                                                          #
#   see classes                                                        #
#                                                                      #
# Classes :                                                            #
#   dbc                                                                #
#       $resLink - used for sql link object                            #
#       $boolDbSelected - used for database connect boolean true if    #
#           database was selected false if couldn't connect            #
#       $arrDBC stores input of $arrDBC from construct see construct   #
#       construct takes input of $arrDBC                               #
#           $arrDBC - Database Connection Array                        #
#               [host] => host name                                    #
#               [user] => sql User                                     #
#               [pass] => sql Pass                                     #
#               [db] => database name or 0 for no database             #
#               [type] => database type,                               #
#               [xhtml] => use xhtml boolean                           #
#               [error] => die or string                               #
#       get_arrDBC() - returns arrDBC from construct input             #
#       get_type() - returns arrDBC['type']                            #
#       get_xhtml() - returns arrDBC['xhtml'] boolean                  #
#       sql_connect() - uses constructs arrDBC to connect to           #
#           mysql/mssql database                                       #
#       sql_close() - takes input of sql type (get type or             #
#           arrDBC['type']) uses class var to close open db            #
#       query() - runs sql query                                       #
#       query_all() -                                                  #
#       query_all_where() -                                            #
#       maif() - mysql auto increment filler                           #
#                                                                      #
# CSS :                                                                #
#   db_error - used in span for custom database errors                 #
#   db_sql_error_message - used in span for SQL default error          #
#       messages and error numbers                                     #
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
########################################################################
# Class dbc                                                            #
#   DataBase Connect Class                                             #
########################################################################
class dbc
{
	// database link resource
	var $resLink;
	// bool for selected database if 1 its selected if 0 failed to use
	//   uses arrDBC['db']
	var $boolDbSelected;
	// if arrdbc['db'] is equal to 0 defined by construct.. set flag for
	// using database or not.
	var $boolUseDB;
	// database connect array
	var $arrDBC;
	// not implimented.
	var $strError;
	####################################################################
	# Constructor                                                      #
	####################################################################
	// if you use php 4 use function dbc instead of construct
	#function dbc($arrDBC)
	public function __construct($arrDBC)
	{
		// make sure $arrDBC construct data is array and not empty
		if ($arrDBC > 0 && is_array($arrDBC))
		{
			$this->arrDBC = $arrDBC;
			// if error isn't set or set improperly set it to die
			if ($this->arrDBC['error'] == NULL)
			{
				$this->arrDBC['error'] = 'die';
			}
			#elseif ($this->arrDBC['error'] != 'die' || $this->arrDBC['error'] != 'string') {
			elseif ($this->arrDBC['error'] != 'die')
			{
				if ($this->arrDBC['error'] != 'string')
				{
					$this->arrDBC['error'] = 'die';
				}
			}
			// set usedatabase boolean
			if ($this->arrDBC['db'] == '0')
			{
				$this->boolUseDB = 0;
			}
			else
			{
				$this->boolUseDB = 1;
			}
		}
		// if arrDBC is not array or is null
		else
		{
			// I don't think the wrap will work here. should be a generic message here maybe
			$this->strError .= $this->wrap_error('Invalid variables passed for "sql_connect"');
			if ($this->arrDBC['error'] == 'die')
				die ($this->strError);
		}
		#Debug
		#	echo "arrDBC:\n<pre>\n"; print_r($arrDBC); echo "</pre>\n";
		#	echo "this->arrDBC:\n<pre>\n"; print_r($this->arrDBC); echo "</pre>\n";
	}
	####################################################################
	#   get_arrDBC                                                     #
	####################################################################
	function get_arrDBC()
	{
		return $this->arrDBC;
	}
	####################################################################
	# Gets for arrDBC info                                             #
	####################################################################
	function get_type()
	{
		return $this->arrDBC['type'];
	}
	function get_xhtml()
	{
		return $this->arrDBC['xhtml'];
	}
	####################################################################
	# Gets for strError (only if arrdbc['error'] is string             #
	####################################################################
	function get_error_string()
	{
		return $this->strError;
	}
	####################################################################
	# wrap_error function                                              #
	####################################################################
	function wrap_error($strError)
	{
		$strError = $this->arrDBC['wraperror']['0'].$strError.$this->arrDBC['wraperror']['1'];
		return $strError;
	}
	####################################################################
	# sql_connect function                                             #
	#       uses arrDBC                                                #
	####################################################################
	function sql_connect()
	{
		// link to db server or error and exit
		################################################################
		# mysql                                                        #
		################################################################
		if ($this->arrDBC['type'] == 'mysql')
		{
			if(function_exists('mysql_connect'))
			{
				$this->resLink = mysql_connect($this->arrDBC['host'], $this->arrDBC['user'], $this->arrDBC['pass']);
				// check for errors
				if (!$this->resLink)
				{
					$this->strError .= $this->wrap_error('Could not connect to database server : ('.mysql_errno().') '.mysql_error());
					if ($this->arrDBC['error'] == 'die')
						die ($this->strError);
				}
				// check to see if we should connect to a database
				if ($this->boolUseDB)
				{
					// connect to db or error and exit
					$this->boolDbSelected = mysql_select_db($this->arrDBC['db'], $this->resLink);
					if (!$this->boolDbSelected)
					{
						$this->strError .= $this->wrap_error('Could not connect to database '.$this->arrDBC['db'].'('.mysql_errno().') '.mysql_error());
						if ($this->arrDBC['error'] == 'die')
							die ($this->strError);
					}
				}
			}
			// if mysql_connect function does not exist run error
			else
			{
				$this->strError .= $this->wrap_error('mysql_connect function does not exist. Most likely module for mysql is not loaded');
				if ($this->arrDBC['error'] == 'die')
					die ($this->strError);
			}
		}
		################################################################
		# mssql                                                        #
		################################################################
		elseif ($this->arrDBC['type'] == 'mssql')
		{
			// check to see if function exists (e.g. php is compiled properly)
			if(function_exists('mssql_connect'))
			{
				$this->resLink = mssql_connect($this->arrDBC['host'], $this->arrDBC['user'], $this->arrDBC['pass']);
				if (!$this->resLink)
				{
					if ($this->arrDBC['error'] == 'die') 
						die ('<span class="db_error">Could not connect to database server :</span>'.$br."\n");
					elseif ($this->arrDBC['error'] == 'string')
						$this->strError = '<span class="db_error">Could not connect to database server :</span>'.$br."\n";
				}
				// check to see if we should connect to a database
				if ($this->boolUseDB)
				{
				// connect to db or error and exit
					$this->boolDbSelected = mssql_select_db($this->arrDBC['db'], $this->resLink);
					if (!$this->boolDbSelected)
					{
						if ($this->arrDBC['error'] == 'die') 
							die ('<span class="db_error">Could not connect to database '.$this->arrDBC['db'].' :</span>'.$br."\n");
						elseif ($this->arrDBC['error'] == 'string')
							$this->strError = '<span class="db_error">Could not connect to database '.$this->arrDBC['db'].' :</span>'.$br."\n";
					}
				}
			}
			// if mssql_connect does not exist error
			else
			{
				if ($this->arrDBC['error'] == 'die') 
					die ('<span class="db_error">mssql_connect function does not exist. Most likely module for mssql is not loaded</span>'.$br."\n");
				elseif ($this->arrDBC['error'] == 'string')
					$this->strError = '<span class="db_error">mssql_connect function does not exist. Most likely module for mssql is not loaded</span>'.$br."\n";
			}
		}
		################################################################
		# no valid database type                                       #
		#   other fail                                                 #
		################################################################
		else
		{
			if ($this->arrDBC['error'] == 'die')
				die ('<span class="db_error">UnSupported Database Type for "connect"</span>'.$br."\n");
			elseif ($this->arrDBC['error'] == 'string')
				$this->strError = '<span class="db_error">UnSupported Database Type for "connect"</span>'.$br."\n";
		}
	}
	####################################################################
	# sql_close function                                               #
	####################################################################
	function sql_close()
	{
		// get database type
		################################################################
		# mysql                                                        #
		################################################################
		if ($this->get_type() == 'mysql')
		{
			mysql_close($this->resLink);
		}
		################################################################
		# mssql                                                        #
		################################################################
		elseif ($this->get_type() == 'mssql')
		{
			mssql_close($this->resLink);
		}
		################################################################
		# invalid database type                                        #
		################################################################
		else
		{
			$this->strError .= $this->wrap_error('UnSupported Database Type for "Close"');
			if ($this->arrDBC['error'] == 'die')
				die ($this->strError);
		}
	}
	####################################################################
	# query                                                            #
	#   Input :                                                        #
	#       $strQuery - sql query                                      #
	#       $strType - database type                                   #
	#       $boolXhtml - if 1 use xhtml style element tags.            #
	#       example query('select * from table', $arrDBC['xhtml']);    #
	####################################################################
	function query($strQuery, $boolConnectDBC = false)
	{
		// get database type
		$strType = $this->get_type();
		// get xhtml boolean
		#$boolXhtml = $this->get_xhtml();
		// if xhtml is 1 set <br /> else set <br>
		#$boolXhtml ? $br = '<br />' : $br = '<br>';
		if ($boolConnectDBC) $this->sql_connect();
		if ($strQuery != NULL)
		{
			// run query or error then continue
			// mysql
			if ($strType == 'mysql')
			{
				$resResult = mysql_query($strQuery);
				if (!$resResult)
				{
					$strError = $this->wrap_error('Could not successfully run query "'.$strQuery.'"('.mysql_errno().') '.mysql_error());
					if ($this->arrDBC['error'] == 'die')
						die ($strError);
					elseif ($this->arrDBC['error'] == 'string')
						#$this->strError = '<span class="db_error">Could not successfully run query</span> <span class="db_invalid_query">"'.$strQuery.'"</span> <span class="db_sql_error_message">('.mysql_errno().') '.mysql_error().'</span>'.$br."\n";
						$this->strError = $strError;
				}
			}
			//mssql
			elseif ($strType == 'mssql')
			{
				$resResult = mssql_query($strQuery);
				if (!$resResult)
				{
					if ($this->arrDBC['error'] == 'die')
						die ('<span class="db_error">Could not successfully run query</span> <span class="db_invalid_query">"'.$strQuery.'"</span>'.$br."\n");
					elseif ($this->arrDBC['error'] == 'string')
						$this->strError = '<span class="db_error">Could not successfully run query</span> <span class="db_invalid_query">"'.$strQuery.'"</span>'.$br."\n";
				}
			}
			// other fail
			else
			{
				if ($this->arrDBC['error'] == 'die')
					die ('<span class="db_error">UnSupported Database Type for "query"</span>'.$br."\n");
				elseif ($this->arrDBC['error'] == 'string')
					$this->strError = '<span class="db_error">UnSupported Database Type for "query"</span>'.$br."\n";
			}
		}
		else
		{
			if ($this->arrDBC['error'] == 'die')
				die ('<span class="db_error">Invalid variables passed for "query"<span>'.$br."\n");
			elseif ($this->arrDBC['error'] == 'string')
				$this->strError = '<span class="db_error">Invalid variables passed for "query"<span>'.$br."\n";
		}
		if ($boolConnectDBC) $this->sql_close;
	return $resResult;
	}
	####################################################################
	#   query_all                                                      #
	#   Input :                                                        #
	#       $strTbl - table name                                       #
	#       $strType - database type (default: mysql)                  #
	#       $boolXhtml - if true use xhtml style element tags          #
	#           (default: null)                                        #
	####################################################################
	function query_all($strTbl, $strType = 'mysql', $boolXhtml = NULL)
	{
		// if xhtml is 1 set <br /> else set <br>
		$boolXhtml ? $br = '<br />' : $br = '<br>';
		if ($strTbl != NULL || $strColId != NULL || $strId != NULL) {
			$strQuery = "select * from $strTbl";
			$resResult = $this->query($strQuery, $strType, $boolXhtml);
		}
		else {
			if ($this->arrDBC['error'] == 'die')
				die ('<span class="db_error">Invalid variables passed for "query_all_where"<span>'.$br."\n");
			elseif ($this->arrDBC['error'] == 'string')
				$this->strError = '<span class="db_error">Invalid variables passed for "query_all_where"<span>'.$br."\n";
		}
		return $resResult;
	}
	####################################################################
	#   query_all_where                                                #
	#   Input :                                                        #
	#       $strTbl - table name                                       #
	#       $strColId - ID column name                                 #
	#       $strId - ID variable                                       #
	#       $strType - database type (default: mysql)                  #
	#       $boolXhtml - if 1 use xhtml style element tags             #
	#           (default: null)                                        #
	####################################################################
	function query_all_where($strTbl, $strColId, $strId, $strType = 'mysql', $boolXhtml = NULL)
	{
		// if xhtml is 1 set <br /> else set <br>
		$boolXhtml ? $br = '<br />' : $br = '<br>';
		if ($strTbl != NULL || $strColId != NULL || $strId != NULL)
		{
			$strQuery = "select * 
				from $strTbl 
				where $strColId = '$strId'";
			$resResult = $this->query($strQuery, $strType, $boolXhtml);
		}
		else
		{
			if ($this->arrDBC['error'] == 'die')
				die ('<span class="db_error">Invalid variables passed for "query_all_where"<span>'.$br."\n");
			elseif ($this->arrDBC['error'] == 'string')
				$this->strError = '<span class="db_error">Invalid variables passed for "query_all_where"<span>'.$br."\n";
		}
		return $resResult;
	}
	####################################################################
	#   maif                                                           #
	#       Mysql Auto Increment Filler                                #
	#       Auto Increment Filler. mysql                               #
	####################################################################
	function maif($tbl_name, $tbl_id)
	{
		$i = "0";
		$resResult = $this->query('select '.$tbl_id.' from '.$tbl_name.' order by '.$tbl_id.'');
		while ($row = mysql_fetch_assoc($resResult)) {
			$i++;
			if ($i != $row[$tbl_id]) { return($i); }
		} // end while
		mysql_free_result($resResult);
	}
}
# END class dbc ########################################################
?>