<?php
########################################################################
# File Name : benchmark.in.php                                         #
# Author(s) :                                                          #
#   Phil Allen phil@hilands.com                                        #
#   (I really can't take credit for this, as this was taken from       #
#   forums and other websites. Its slightly modified to my liking      #
#   so for this I put it into GPL, I would assume any code in public   #
#   domain should be free, this code is free)                          #
#                                                                      #
# Last Edited By :                                                     #
#   phil@hilands.com                                                   #
# Version : 2005121800                                                 #
#                                                                      #
# Copyright :                                                          #
#   This file is a bench mark script                                   #
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
#   Use this file to benchmark your php script load times, this does   #
#   not account for image load times only your script load             #
#                                                                      #
#   Usage                                                              #
#       include ("./benchmark.inc")                                    #
#       $intStartTime = benchmark_time();                              #
#       $intEndTime = benchmark_time();                                #
#       echo 'Load Time : '.                                           #
#           benchmark_calculate($intStartTime, $intEndTime).           #
#           ' seconds';                                                #
#                                                                      #
# Functions :                                                          #
#    getmtime()                                                        #
#    benchmark_time()                                                  #
#    benchmark_calculate()                                             #
#                                                                      #
# Classes :                                                            #
#    N/A                                                               #
#                                                                      #
# CSS :                                                                #
#    N/A                                                               #
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
# function getmtime()                                                  #
#    get micro time                                                    #
########################################################################
function getmtime()
{
	$arr = explode (' ',microtime());
	$intTime = (float)$arr[0] + (float)$arr[1];
	return ($intTime);
}
########################################################################
# function benchmark_time()                                            #
#    use getmtime function and return microtime to variable in program #
########################################################################
function benchmark_time()
{
	return(getmtime());
}
########################################################################
# function benchmark_calculate(starttime, enddtime)                    #
#    calculate the load time based off of start and end                #
########################################################################
function benchmark_calculate($intStartTime, $intEndTime)
{
	return ($intEndTime - $intStartTime);
}
?>