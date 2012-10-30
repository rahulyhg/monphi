/*
########################################################################
# File Name : display_hide.js                                          #
# Author(s) :                                                          #
#   Phil Allen phil@hilands.com                                        #
# Last Edited By :                                                     #
#   phil@hilands.com                                                   #
# Version : 2007022100                                                 #
#                                                                      #
# Copyright :                                                          #
#   Copyright (C) 2005 Philip J Allen                                  #
#   General Public License (GPL)                                       #
#                                                                      #
# data comes in like elementID;display value                           #
# the display value will most common be block or inline                #
# arrData[0] = element arrData[1] = display value                      #
#                                                                      #
########################################################################
*/
function display_hide_toggle (strDisplayData)
{
	var arrData=strDisplayData.split(";");
	if (document.getElementById(arrData[0]).style.display == 'none')
	{
		document.getElementById(arrData[0]).style.display = arrData[1];
	}
	else
	{
		document.getElementById(arrData[0]).style.display = 'none';
	}
}
function display_hide (strElement)
{
	document.getElementById(strElement).style.display = 'none';
}
function display_block (strElement)
{
	document.getElementById(strElement).style.display = 'block';
}
function display_inline (strElement)
{
	document.getElementById(strElement).style.display = 'inline';
}
