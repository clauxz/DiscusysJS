<?php

$lasttime = isset($_GET['timestamp']) ? $_GET['timestamp'] : '';
$presentationId = isset($_GET['presentationId']) ? $_GET['presentationId'] : '';

if ($presentationId=='')
	return;

// External data file: can use any source, DB etc.
$filename = dirname(__FILE__).'/'.$presentationId.'.txt';
$currenttime = filemtime($filename);
 
// Loop until the files timestamp changes
while($currenttime <= $lasttime) {
    usleep(1000000); // slow it down a bit
    clearstatcache();
    $currenttime = filemtime($filename);
}
 
 $contents = file_get_contents($filename);
  
// Send json to receiver
echo json_encode(array(
    'position' =>  $contents,
    'timestamp' => $currenttime
));
?>