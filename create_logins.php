<?php
include 'config.php';
include 'dbQueries.php';

$action = $_GET['action'];
$type = $_GET['type'];
if($action=='insert')
{
	if($type == 'SuperAdmin')
	{	
		$role = 'Admin';
		$name = $_GET['companyname'];
		$user = $_GET['username'];
		$pwd = $_GET['password'];
		$address = $_GET['address'];
		//$mobile = $_GET['mobile'];
		$regno = $_GET['regno'];
		$alternativeno = $_GET['alternativeno'];
		$image = $_GET['image'];
		$getdetails = check_userlogins($con,$user);
		//print_r($getdetails);
		if(mysqli_num_rows($getdetails)==0) 
		{
			$companyres = insert_companylogin($con,$name,$user,$pwd,$address,$mobile,$regno,$alternativeno,$image);
			$companyid = $companyres['id'];
			$userres = insert_userlogin($con,$companyid,$name,$user,$pwd,$mobile,$role);
			echo json_encode(array('companyid'=>$companyid,'status' => 'success'));
		}
		else
		{
			echo json_encode(array('no_of_results'=>mysqli_num_rows($getdetails),'status' => 'Failed'));
		}
	}
	else
	{
		$companyid = $_GET['companyid'];
		$name = $_GET['name'];
		$pwd = $_GET['password'];
		$user = $_GET['username'];
		/*$getdetails = select_uid($con);
		$uid = mysqli_fetch_array($getdetails);
		$uid = $uid['uid'];
		$uid = $uid+1;
		$user = 'KKCPL'.$uid;*/
		$mobile = $_GET['mobile'];
		$role = $_GET['role'];
		if($role=='driver')
		{
			$mobile = $user;
		}
		$emptype = $_GET['emptype'];
		$getdetails = check_userlogins($con,$user);//,$mobile
		//print_r($getdetails);
		if(mysqli_num_rows($getdetails)==0) 
		{
			$userres = insert_userlogin($con,$companyid,$name,$user,$pwd,$mobile,$role,$emptype);
			//update_uid($con);
			$msg = "Dear $name,Thanks for using Quickorder.co.in as per your request,your login details are,username is $user \n password is ".$pwd;
			$link="http://login.rocktosms.com/api/web2sms.php?workingkey=1499313h210b69009aw9f&sender=VALETT&to=$user&message=$msg";
			//echo "<script>alert('".$link."');</script>"; 
			$url = preg_replace("/ /", "%20", $link);
			$homepage = file_get_contents($url);
			echo json_encode(array('companyid'=>$companyid,'status' => 'success','username'=>$user));
		}
		else
		{
			echo json_encode(array('no_of_results'=>mysqli_num_rows($getdetails),'status' => 'Failed'));
		}
	}
}
if($action=='select')
{
	$username = $_GET['username'];
	$password = $_GET['password'];
	$getdetails = select_userlogins($con,$username,$password);
	//print_r($getdetails);
	if(mysqli_num_rows($getdetails)!=0) 
	{
		//$i=0;
		while($getdata = mysqli_fetch_assoc($getdetails)) 
		{ 
			$result['role'] = $getdata['role']; 
			$result['companyid'] = $getdata['companyid']; 
			$result['name'] = $getdata['name']; 
			//$result['mobile'] = $getdata['mobile']; 
			$result['username'] = $getdata['username']; 
			//$result['password'] = $getdata['password']; 
			$result['emptype'] = $getdata['emptype']; 
			$result['id'] = $getdata['id']; 
			//$roles[] = $result ;
			//$i++;
		}
	}
	if(count($result)=='0')
	{
		$status = 'Failed';
	}
	else
	{
		$status = 'Success';
	}
	echo json_encode(array('no_of_results' => mysqli_num_rows($getdetails),'result'=>$result,'status' =>$status));
}
?>