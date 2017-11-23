<?php
include 'config.php';
include 'dbQueries.php';

$regid = $_GET['regid'];
$username = $_GET['username'];
$getdetails = gcmiddetails($con,$regid,$username);
//print_r($getdetails);
if($getdetails) 
{
	echo json_encode(array('status' => 'success'));
}
else
{
	echo json_encode(array('status' => 'Failed'));
}
?>
