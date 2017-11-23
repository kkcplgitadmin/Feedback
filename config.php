<?php
date_default_timezone_set("Asia/Calcutta");
$now = date('Y-m-d H:i:s');
$parked = 'Parked';
$getvehicle = 'Get Vehicle';
$deliverydue = 'Delivery Due';
$completed = 'Completed';
error_reporting(E_ALL & ~E_NOTICE);
$con = mysqli_connect("localhost","rushfuqn_fedback","fedback@123","rushfuqn_feedback");

if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
//print_r($con);