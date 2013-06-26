<?php
session_start();

$key = ini_get("session.upload_progress.prefix") . "myForm";
if (!empty($_SESSION[$key])) {
    $current = $_SESSION[$key]["bytes_processed"]; // This is the current upload progress
    $total = $_SESSION[$key]["content_length"]; // Total weight of the file
    if($current < $total) 
		{
		echo ceil($current / $total * 100);
		}
		else
		{
		echo "100";
		}
	 echo $current < $total ? ceil($current / $total * 100) : 100;
}
else 
{
    echo "100";
}
?>