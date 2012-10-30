<?php
$resDir = dir("./");
echo "<pre>";
echo "Handle: " . $resDir->handle . "\n";
echo "Path: " . $resDir->path . "\n";
$tmp = "";
while (false !== ($entry = $resDir->read())) {
	if (substr($entry, -8) == "tpl.html")
		$tmp .= $entry."\n";
   echo $entry."\n";
}
$resDir->close();

echo "tpl.html files :";
echo $tmp;
echo "</pre>";
?>

