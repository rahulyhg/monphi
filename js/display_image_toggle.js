/*
################################################################################
# File Name : display_image_toggle.js                                          #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
#   See Notes Below website url for credits                                    #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2009111500                                                         #
#                                                                              #
# Copyright :                                                                  #
#   Copyright (C) 2005 Philip J Allen                                          #
#   General Public License (GPL)                                               #
#                                                                              #
# Image same page overlay display functions                                    #
#    use grayout provided by to toggle the background color when image is      #
#    displayed                                                                 #
# http://www.hunlock.com/blogs/Snippets:_Howto_Grey-Out_The_Screen             #
################################################################################
################################################################################
# Positioning functions provided by tigra                                      #
#   f_clientWidth()                                                            #
#   f_clientHeight()                                                           #
#   f_scrollLeft()                                                             #
#   f_scrollTop()                                                              #
#   f_filterResults(n_win, n_docel, n_body)                                    #
#   f_filterResults is a helper function allowing input from the functions     #
#   listed above.                                                              #
# http://www.softcomplex.com/docs/get_window_size_and_scrollbar_position.html  #
################################################################################
*/
function f_clientWidth() {
	return f_filterResults (
		window.innerWidth ? window.innerWidth : 0,
		document.documentElement ? document.documentElement.clientWidth : 0,
		document.body ? document.body.clientWidth : 0
	);
}
function f_clientHeight() {
	return f_filterResults (
		window.innerHeight ? window.innerHeight : 0,
		document.documentElement ? document.documentElement.clientHeight : 0,
		document.body ? document.body.clientHeight : 0
	);
}
function f_scrollLeft() {
	return f_filterResults (
		window.pageXOffset ? window.pageXOffset : 0,
		document.documentElement ? document.documentElement.scrollLeft : 0,
		document.body ? document.body.scrollLeft : 0
	);
}
function f_scrollTop() {
	return f_filterResults (
		window.pageYOffset ? window.pageYOffset : 0,
		document.documentElement ? document.documentElement.scrollTop : 0,
		document.body ? document.body.scrollTop : 0
	);
}
function f_filterResults(n_win, n_docel, n_body) {
	//document.write (n_win+" : "+n_docel+" : "+n_body+"<br />");
	var n_result = n_win ? n_win : 0;
	//document.write ("first if : "+n_result+"<br />");
	if (n_docel && (!n_result || (n_result > n_docel)))
	{
		//document.write ("Second if : "+n_result+"<br />");
		n_result = n_docel;
	}
	// if n_body is not zero, n_result is not zero
	if (n_body && (!n_result || (n_result > n_body)))
	{
		//document.write ("Third if : "+n_result+"<br />");
		n_result = n_body;
	}
	//n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;
	return n_result;
}
//document.write ("Width : "+f_clientWidth()+"<br />");
//document.write ("Height : "+f_clientHeight()+"<br />");
/*
################################################################################
# display_image_toggle                                                         #
#   input strDisplayData is the id of the object displaying the data in this   #
#   case a div                                                                 #
#                                                                              #
################################################################################
*/
function display_image_toggle (strDisplayData)
{
	var arrData=strDisplayData.split(";");
	if (document.getElementById(arrData[0]).style.display == 'none')
	{
		document.getElementById(arrData[0]).style.display = 'block';
		document.getElementById(arrData[0]).style.zIndex=1000;
		var posTop = (f_clientHeight()/2)-(document.getElementById(arrData[0]).offsetHeight/2)+f_scrollTop();
		var posLeft = (f_clientWidth()/2)-(document.getElementById(arrData[0]).offsetWidth/2);
		//document.write ("TOP : "+posTop+"<br />LEFT : "+posLeft+"<br />");
		if (posTop < 0) posTop=0;
		if (posLeft < 0) posLeft=0;
		document.getElementById(arrData[0]).style.top= posTop+"px";
		document.getElementById(arrData[0]).style.left= posLeft+"px";
	}
	else
	{
		document.getElementById(arrData[0]).style.display = 'none';
	}
}
