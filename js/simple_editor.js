/*
################################################################################
# File Name : simple_editor.js - SE                                            #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2010012200                                                         #
# References :                                                                 #
#    http://the-stickman.com/web-development/javascript/finding-selection-cursor-position-in-a-textarea-in-internet-explorer/
################################################################################
*/
function se_append (strTarget, strAddition)
{
	document.getElementById(strTarget).value += strAddition;
}
function se_append_selected(strTarget, strSAddition, strEAddition)
{
	txtarea = document.getElementById(strTarget);
	// for everything but IE
	if (window.getSelection)
	{
		// we do nothing here. as all browsers but IE work..
	}
	// IE fix? no this is not.. um it selects from anywhere in the document.. sheesh
	else if(document.selection)
	{
		// The current selection
		var range = document.selection.createRange();
		// We'll use this as a 'dummy'
		var stored_range = range.duplicate();
		// Select all text
		stored_range.moveToElementText(txtarea);
		// Now move 'dummy' end point to end point of original range
		stored_range.setEndPoint( 'EndToEnd', range );
		// Now we can calculate start and end points
		txtarea.selectionStart = stored_range.text.length - range.text.length;
		txtarea.selectionEnd = txtarea.selectionStart + range.text.length;
	}
		var x=txtarea.selectionEnd;
		//alert(x);


	// determine if values are the same. if true append
	//alert(txtarea.selectionStart+':'+txtarea.selectionEnd);
	//alert(start+':'+end);
//	if (txtarea.selectionStart == txtarea.selectionEnd)
//	{
		//txtarea.value += strSAddition+strEAddition
//	}
	// if false we should have a selection (should vary with IE as text selection is entire page relavent not textarea.)
//	else
//	{
		var txtstart = (txtarea.value).substring(0, txtarea.selectionStart);
		var txtmiddle = (txtarea.value).substring(txtarea.selectionStart,txtarea.selectionEnd);  
		var txtend = (txtarea.value).substring(txtarea.selectionEnd,txtarea.value.length);
		//alert(txtstart+strSAddition+txtmiddle+strEAddition+txtend);
		txtarea.value = txtstart+strSAddition+txtmiddle+strEAddition+txtend
		//alert(txtarea.selectionEnd);

		setSelRange(txtarea, x, x);
		//txtarea.setSelectionRange(txtarea.selectionEnd,txtarea.selectionEnd);
		//txtarea.focus();
		//txtarea.moveStart("character", selection);
		//alert(txtstart+':'+txtarea.txt);
		// after we set focus we need to set position. Bi
		//txtarea.setCursorPosition(txtarea.selectionEnd);
		//txtarea.setCursorPosition(txtarea.selectionStart);
		//txtarea.setSelectionRange(txtarea.selectionEnd,txtarea.selectionStart);
		//txtarea.select();
		//http://stackoverflow.com/questions/512528/set-cursor-position-in-html-textbox
//	}
}


//http://www.webmasterworld.com/forum91/4527.htm
function setSelRange(inputEl, selStart, selEnd) { 
 if (inputEl.setSelectionRange) { 
  inputEl.focus(); 
  inputEl.setSelectionRange(selStart, selEnd); 
 } else if (inputEl.createTextRange) { 
  var range = inputEl.createTextRange(); 
  range.collapse(true); 
  range.moveEnd('character', selEnd); 
  range.moveStart('character', selStart); 
  range.select(); 
 } 
}
function se_replace(strReplace, strOldVal, strNewVal)
{
	while(strReplace.indexOf(strOldVal) != -1)
	{
		strReplace = strReplace.replace(strOldVal, strNewVal);
	}
	return strReplace;
}

/*
################################################################################
# HTML Code                                                                    #
################################################################################
*/
function se_html_insert_url (strTarget)
{
	var thisURL = prompt("Please Enter the URL of the link you are adding", "http://");
	var thisTitle = prompt("Now enter the title of the webpage you wish to reference.", "web page");
	var strInsert = "<a href=\"" + thisURL + "\">" + thisTitle + "</a>";
	se_append(strTarget, strInsert);
	return;
}
function se_html_insert_img (strTarget)
{
	var thisURL = prompt("Please Enter the URL of the image you are adding", "http://");
	var strInsert = "<img src=\"" + thisURL + "\" />";
	se_append(strTarget, strInsert);
	return;
}

/*
################################################################################
# BB CODE                                                                      #
################################################################################
*/
function se_bb_insert_url (strTarget)
{
	var thisURL = prompt("Please Enter the URL of the link you are adding", "http://");
	var thisTitle = prompt("Now enter the title of the webpage you wish to reference.", "web page");
	var strInsert = "[url=\"" + thisURL + "\"]" + thisTitle + "[/url]";
	se_append(strTarget, strInsert);
	return;
}



//this is purely for entertainment and should not be used.
function se_bb_preview()
{
	var txtarea = document.getElementById('editor');
	var preview = document.getElementById('preview');
	txtarea.value.replace(/[b]/, '<b>');
	var string = txtarea.value
	// shit wee need to make a loop for this.. as it only replaces the first found?
	//alert(string);
	var arrReplace=new Array(
		'[b]', '<b>',
		'[/b]', '</b>',
		'\n', '<br />',
		'[u]', '<u>',
		'[/u]', '</u>',
		'[i]', '<i>',
		'[/i]', '</i>',
		'[center]', '<div style=\"text-align:center;\">',
		'[/center]', '</div>',
		'[left]', '<div style=\"text-align:left;\">',
		'[/left]', '</div>',
		'[right]', '<div style=\"text-align:right;\">',
		'[/right]', '</div>'
	);
	for (i=0;i<arrReplace.length;i++)
	{
		string = se_bb_replace(string, arrReplace[i], arrReplace[++i]);
	}
	preview.innerHTML = string;
}