<?php
echo '<pre>'; print_r($_GET); echo '</pre>';
echo "\n\n";
echo '<a href="'.$_SERVER['PHP_SELF'].'">self</a>';
echo "<br /><br />\n\n";
echo '<a href="'.$_SERVER['PHP_SELF'].'?variable=123">?variable=123</a>';
echo "<br /><br />\n\n";
echo '<a href="'.$_SERVER['PHP_SELF'].'?variable=123&poop=brown&chickens=tasty">?variable=123&poop=brown&chickens=tasty</a>';
echo "<br /><br />\n\n";

#echo '<a href="'.$_SERVER['REQUEST_URI'].'?variable=123">variable=123</a>';

?>

