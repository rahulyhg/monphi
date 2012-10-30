function pop_thumb (imagedata)
{
	var image_array=imagedata.split(";");
	win = window.open ("", "win", "scrollbars,resizable,width="+image_array[2]+",height="+image_array[3])
	win.document.write('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"\n');
	win.document.write('	"http://www.w3.org/TR/html4/loose.dtd">\n');
	win.document.write('<html>\n<head>\n	<title>'+image_array[1]+'</title>\n');
	win.document.write('<style type="text/css" media="screen">');
	win.document.write('	@import url("../css/base.css");');
	win.document.write('	@import url("../css/format.css");');
	win.document.write('	@import url("../css/popup.css");');
	win.document.write('</style>');
	win.document.write('</head>\n');
	win.document.write('	<body>\n		<img src="'+image_array[0]+'">\n		<br>\n');
	win.document.write('		<div class="center">\n'+image_array[4]+'\n		</div>\n');
	win.document.write('	</body>\n</html>');
	win.document.close();
}

function winpop (url)
{
	win = window.open (url, "win", "scrollbars,resizable,width=525,height=375")
}
/*
data comes in like url;windowname;width;height
this means
arrUrl[0] = url 1 = window name 2 = width 3 = height
*/
function winpop2 (strUrlData)
{
	var arrUrl=strUrlData.split(";");
	var strData = 'scrollbars, resizable, width='+arrUrl[2]+',height='+arrUrl[3]
	win = window.open (arrUrl[0], arrUrl[1], strData)
}