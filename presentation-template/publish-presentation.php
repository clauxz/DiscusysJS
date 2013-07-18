<?php
// External data source
$presentationId = isset($_GET['presentationId']) ? $_GET['presentationId'] : '';

if ($presentationId=='')
	return;

$filename = dirname(__FILE__).'/'.$presentationId.'.txt';
 
// Write direction to file
$isComplete = $_GET['isComplete'];
$position = $_GET['position'];

$fh = fopen($filename, 'w') or die('Cant open');
fwrite($fh, $_GET['position']);
fclose($fh);
?>