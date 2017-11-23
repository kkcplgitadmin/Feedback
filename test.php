<?php
include 'config.php';
include 'dbQueries.php';
$getdetails = select_uid($con);
$uid = mysqli_fetch_array($getdetails);
$uid = $uid['uid'];
echo $uid+1;
?>