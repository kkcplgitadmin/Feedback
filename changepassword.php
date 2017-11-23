<?php
include 'config.php';
include 'dbQueries.php';

$action = $_GET['action'];
$type = $_GET['type'];
if($action=='select')
{
	$username = $_GET['username'];
	//$password = $_GET['password'];
	$getpwd = select_getpassword($con,$username);
	//print_r($getdetails);
	if(mysqli_num_rows($getpwd)!=0) 
	{
		//$i=0;
		$getdata = mysqli_fetch_array($getpwd);		
		$password = $getdata['password']; 
		//$result['mobile'] = $getdata['mobile']; 
		$result['id'] = $getdata['id']; 
			
	}
	if(count($result)=='0')
	{
		$status = 'Failed';
	}
	else
	{
		$mobile = $getdata['username'];
		$msg = "Dear User, Thanks for using Quickorder.co.in as per your request,your login password is ".$password;
		$link="http://login.rocktosms.com/api/web2sms.php?workingkey=1499313h210b69009aw9f&sender=VALETT&to=$mobile&message=$msg";
		//echo "<script>alert('".$link."');</script>"; 
		$url = preg_replace("/ /", "%20", $link);
		$homepage = file_get_contents($url);
		//echo "Message sent successfully";
		$status = 'Success';
	}
	echo json_encode(array('no_of_results' => mysqli_num_rows($getpwd),'result'=>$result,'status' =>$status));
}
if($action=='update')
{
	$username = $_GET['username'];
	$password = $_GET['password'];
	$newpassword = $_GET['newpassword'];
	$getdetails = select_userlogins($con,$username,$password);
	//print_r($getdetails);
	if(mysqli_num_rows($getdetails)!=0) 
	{
		//$i=0;
		$res = update_userdata($con,$username,$newpassword);
		if($res)
		{
			$status = 'Success';
			$msg = "Dear User, Thanks for using Quickorder.co.in as per your request,your new login password is ".$newpassword;
			$link="http://login.rocktosms.com/api/web2sms.php?workingkey=1499313h210b69009aw9f&sender=VALETT&to=$username&message=$msg";
			//echo "<script>alert('".$link."');</script>"; 
			$url = preg_replace("/ /", "%20", $link);
			$homepage = file_get_contents($url);
			echo json_encode(array('no_of_results' => mysqli_num_rows($getdetails),'result'=>array(),'status' =>$status));
		}
		else
		{
			$status = 'Failure';
			echo json_encode(array('no_of_results' => mysqli_num_rows($getdetails),'result'=>array(),'status' =>$status));
		}
	}
	
}
?>