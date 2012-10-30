<?php
########################################################################
# File Name : arrstripslashes.inc.php                                  #
# Author(s) :                                                          #
#   Phil Allen phil@hilands.com                                        #
# Last Edited By :                                                     #
#   phil@hilands.com                                                   #
# Version : 2006020100                                                 #
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
#   Currently N/A                                                      #
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
########################################################################
# Function arrstripslashes($input)                                     #
#                                                                      #
#   Input                                                              #
#       Input can either be a string, array or nested array            #
#   Returns                                                            #
#       an identical string, array or nested array with the slashes    #
#       stripped                                                       #
#                                                                      #
#                                                                      #
########################################################################


function arrstripslashes($input) {
	if (!is_array($input)) {
# Debug
#	echo 'not an array';
		$input = stripslashes($input);
	}
	else {
# Debug
#	echo 'is array';
		foreach ($input as $key => $val) {
			if (is_array($input[$key])) {
				$input[$key] = arrstripslashes($input[$key]);
			}
			else {
				$input[$key] = stripslashes($input[$key]);
			}
		}
	}
	return $input;
}
?>