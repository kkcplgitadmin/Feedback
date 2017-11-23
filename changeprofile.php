<?php
include 'config.php';
include 'dbQueries.php';

$id = $_GET['id'];
$mobile = $_GET['mobile'];
$name = $_GET['name'];
$emptype = $_GET['emptype'];
$status = $_GET['status'];
$getdetails = update_profiledetails($con,$id,$mobile,$name,$emptype,$status);

if($getdetails)
{
	echo json_encode(array('status' => 'success'));
}
else
{
	echo json_encode(array('status' => 'Failed'));
}

/*$msg = "Dear User, Thanks for using Quickorder.co.in as per your request,your login password is ".$password;
$link="http://login.rocktosms.com/api/web2sms.php?workingkey=1499313h210b69009aw9f&sender=RUSHME&to=$mobile&message=$msg";
//echo "<script>alert('".$link."');</script>"; 
$url = preg_replace("/ /", "%20", $link);
$homepage = file_get_contents($url);
//echo "Message sent successfully";
$status = 'Success';*/
?>