<?php
include 'config.php';
include 'dbQueries.php';
include 'gcm_notifications.php';

$action = $_GET['action'];

if($action == 'insert')
{
	$name = $_GET['name'];
	$mobile = $_GET['mobile'];
	$email = $_GET['email'];
	$dob = $_GET['dob'];
	$description = $_GET['description'];
	$tokenid = $_GET['tokenid'];
	$companyid = $_GET['companyid'];
	//$porterid = $_GET['porterid'];
	$food = $_GET['food'];
	$service = $_GET['service'];
	$order_service_time = $_GET['order_service_time'];
	$porter_name = $_GET['porter_name'];
	$porter_rating = $_GET['porter_rating'];

	$fbres = insert_feedback($con,$name,$mobile,$email,$dob,$description,$tokenid,$companyid,$food,$service,$order_service_time,$porter_name,$porter_rating);
	if($fbres && $tokenid!='')
	{
		$vres = insert_valet($con,$name,$mobile,$email,$dob,$description,$tokenid,$companyid,$porter_name);
		if($vres)
		{
			$getids = get_valetadmin($con,$companyid);
			$getdata = mysqli_fetch_array($getids);
			//print_r($getdata);
			//exit;
			$username = $getdata['username'];
			$tokenres = select_vehicledetailsbytokenid($con,$tokenid);
			$tokendata = mysqli_fetch_array($tokenres);
			$vehicleno = $tokendata['vehicleno'];
			$subvehicleno = $tokendata['subvehicleno'];
			$data = array('shorttext'=>'Vehicle No: '.$vehicleno.$subvehicleno,'longtext'=>$tokenid.' is ready for checkout','tokenid'=>$tokenid,'status' =>'success','role'=>'valetadmin');
			//$data = array('tokenid'=>$tokenid,'status' =>'success');
			$getgcmidres = select_gcmid($con,$username);
			$getrow = mysqli_fetch_array($getgcmidres);
			//print_r($getrow);
			//exit;
			$gcmid = $getrow['gcmid'];
			//echo $gcmid;
			$res = sendMessage($data,$gcmid);
			//print_r($res);
			if($res)
			{
				echo json_encode(array('status' => 'Success'));
			}
			else
			{
				echo json_encode(array('status' => 'Failed'));
			}
		}
		else
		{
			echo json_encode(array('status' => 'Failed'));
		}
	}
	else if($fbres && $tokenid=='')
	{
		$msg = "Dear Customer, Thank you for taking the time to provide us with your valuable feedback.";
		$link="http://login.rocktosms.com/api/web2sms.php?workingkey=1499313h210b69009aw9f&sender=PORTER&to=$mobile&message=$msg";
		//echo "<script>alert('".$link."');</script>"; 
		$url = preg_replace("/ /", "%20", $link);
		$homepage = file_get_contents($url);
		echo json_encode(array('status' => 'Success'));
	}
	else
	{
		echo json_encode(array('status' => 'Failed'));
	}
}
if($action == 'select')
{
	$companyid = $_GET['companyid'];
	$status = $_GET['status'];
	$fbres = select_valetdetails($con,$companyid,$status);
	if(mysqli_num_rows($fbres)!=0) 
	{
		//$getdata = mysqli_fetch_assoc($fbres);
		//print_r($getdata);

		while($getdata = mysqli_fetch_assoc($fbres)) 
		{ 
			$result['name'] = $getdata['name']; 
			$result['companyid'] = $getdata['companyid']; 
			$result['tokenid'] = $getdata['tokenid']; 
			$result['mobile'] = $getdata['mobile'];
			$result['status'] = $getdata['status'];
			$result['ackstatus'] = $getdata['ackstatus'];
			$result['time'] = $getdata['inserted_timestamp'];
			$result['id'] = $getdata['id'];
			$valet[] = $result ;
		}
		//print_r($valet);
	}
	
	echo json_encode(array('no_of_results' => count($valet),'result'=>$valet,'status' => 'success'));
}
?>