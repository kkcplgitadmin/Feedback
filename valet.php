<?php
include 'config.php';
include 'dbQueries.php';
include 'gcm_notifications.php';

$action = $_GET['action'];

if($action=='insert')
{	
	$name = $_GET['name'];
	$mobile = $_GET['mobile'];
	$tokenid = $_GET['tokenid'];
	$companyid = $_GET['companyid'];
	$porter_name = $_GET['porter_name'];
	$tokenres = select_vehicledetailsbytokenid($con,$tokenid);
	$tokendata = mysqli_fetch_array($tokenres);
	$vehicleno = $tokendata['vehicleno'];
	$subvehicleno = $tokendata['subvehicleno'];
	$vres = insert_valet($con,$name,$mobile,'NA','NA','NA',$tokenid,$companyid,$porter_name);
	//$vres = insert_valet($con,$name,$mobile,$email,$dob,$description,$tokenid,$companyid);
	if($vres)
	{
		$getids = get_valetadmin($con,$companyid);
		$getdata = mysqli_fetch_array($getids);
		//print_r($getdata);
		//exit;
		$username = $getdata['username'];
		$data = array('shorttext'=>'Vehicle No: '.$vehicleno.$subvehicleno,'longtext'=>'Token No '.$tokenid.' has requested to go','tokenid'=>$tokenid,'status' =>'success','role'=>'valetadmin');
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
}

if($action=='update')
{	
	$id = $_GET['id'];
	$valetname = $_GET['valetname'];
	//$gcm_id = $_GET['gcmid'];
	$getdetails = update_valetdetails($con,$id,$valetname);
	//print_r($getdetails);
	if($getdetails) 
	{
		$getdetails = select_valetbyid($con,$valetname);
		if(mysqli_num_rows($getdetails)!=0) 
		{
			//$i=0;
			while($getdata = mysqli_fetch_assoc($getdetails)) 
			{ 
				$result['companyid'] = $getdata['companyid']; 
				$result['name'] = $getdata['name']; 
				$result['mobile'] = $getdata['mobile']; 
				$result['email'] = $getdata['email']; 
				$result['tokenid'] = $getdata['tokenid']; 
				$result['id'] = $getdata['id']; 
				$result['assign'] = $getdata['assign']; 
				$result['inserted_timestamp'] = $getdata['inserted_timestamp'];
				$valet[] = $result ;
				//$i++;
			}
			$data = array('no_of_results' => mysqli_num_rows($getdetails),'result'=>$valet,'status' =>'success');
			$getgcmidres = select_gcmid($con,$valetname);
			$getrow = mysqli_fetch_array($getgcmidres);
			$gcmid = $getrow['gcmid'];
			$res = sendMessage($data,$gcmid);
		}
		echo json_encode(array('status' => 'success'));
	}
	else
	{
		echo json_encode(array('status' => 'Failed'));
	}
}
if($action=='select')
{
	$companyid = $_GET['companyid'];
	$getdetails = select_valets($con,$companyid);
	//print_r($getdetails);
	//echo mysqli_num_rows($getdetails);
	if(mysqli_num_rows($getdetails)!=0) 
	{
		//$i=0;
		while($getdata = mysqli_fetch_assoc($getdetails)) 
		{ 
			$result['companyid'] = $getdata['companyid']; 
			$result['name'] = $getdata['name']; 
			$result['mobile'] = $getdata['mobile']; 
			$result['username'] = $getdata['username']; 
			$result['emptype'] = $getdata['emptype']; 
			$result['role'] = $getdata['role']; 
			$result['status'] = $getdata['status']; 
			$result['id'] = $getdata['id']; 
			$roles[] = $result ;
			//$i++;
		}
		//print_r($roles);
	}
	if(count($result)=='0')
	{
		$status = 'Failed';
	}
	else
	{
		$status = 'Success';
	}
	echo json_encode(array('no_of_results' => mysqli_num_rows($getdetails),'result'=>$roles,'status' =>$status));
}
if($action=='getdrivers')
{
	$companyid = $_GET['companyid'];
	$getdetails = select_availabledrivers($con,$companyid);
	//print_r($getdetails);
	//echo mysqli_num_rows($getdetails);
	if(mysqli_num_rows($getdetails)!=0) 
	{
		//$i=0;
		while($getdata = mysqli_fetch_assoc($getdetails)) 
		{ 
			$result['companyid'] = $getdata['companyid']; 
			$result['name'] = $getdata['name']; 
			$result['mobile'] = $getdata['mobile']; 
			$result['username'] = $getdata['username']; 
			$result['emptype'] = $getdata['emptype']; 
			$result['status'] = $getdata['status']; 
			$result['id'] = $getdata['id']; 
			$roles[] = $result ;
			//$i++;
		}
		//print_r($roles);
	}
	if(count($result)=='0')
	{
		$status = 'Failed';
	}
	else
	{
		$status = 'Success';
	}
	echo json_encode(array('no_of_results' => mysqli_num_rows($getdetails),'result'=>$roles,'status' =>$status));
}
if($action=='get')
{
	$companyid = $_GET['companyid'];
	$getdetails = select_allvaletdetails($con,$companyid);
	//print_r($getdetails);
	//echo mysqli_num_rows($getdetails);
	if(mysqli_num_rows($getdetails)!=0) 
	{
		//$i=0;
		while($getdata = mysqli_fetch_assoc($getdetails)) 
		{ 
			$result['companyid'] = $getdata['companyid']; 
			$result['name'] = $getdata['name']; 
			$result['mobile'] = $getdata['mobile']; 
			$result['tokenid'] = $getdata['tokenid']; 
			$result['status'] = $getdata['status']; 
			$result['ackstatus'] = $getdata['ackstatus']; 
			//$result['role'] = $getdata['role']; 
			$result['inserted_timestamp'] = $getdata['inserted_timestamp'];
			$result['id'] = $getdata['id']; 
			$roles[] = $result ;
			//$i++;
		}
		//print_r($roles);
	}
	if(count($result)=='0')
	{
		$status = 'Failed';
	}
	else
	{
		$status = 'Success';
	}
	echo json_encode(array('no_of_results' => mysqli_num_rows($getdetails),'result'=>$roles,'status' =>$status));
}
if($action=='ack')
{
	$id = $_GET['id'];
	//$valetname = $_GET['valetname'];
	//$gcm_id = $_GET['gcmid'];
	$update_valet = ack_valetdetails($con,$id);

	$getdata = mysqli_fetch_array($update_valet);
	$porterid = $getdata['porterid'];
	$tokenid = $getdata['tokenid'];
	//$username = $getdata['username'];
	$tokenres = select_vehicledetailsbytokenid($con,$tokenid);
	$tokendata = mysqli_fetch_array($tokenres);
	$vehicleno = $tokendata['vehicleno'];
	$subvehicleno = $tokendata['subvehicleno'];
	$data = array('shorttext'=>'Vehicle No: '.$vehicleno.$subvehicleno,'longtext'=>'Token No '.$tokenid.' has requested to go','tokenid'=>$tokenid,'status' =>'success','role'=>'porter');
	$getgcmidres = select_gcmid($con,$porterid);
	$getrow = mysqli_fetch_array($getgcmidres);
	//print_r($getrow);
	//exit;
	$gcmid = $getrow['gcmid'];
	//echo $gcmid;
	$res = sendMessage($data,$gcmid);
	if($update_valet)
	{
		$status = 'Success';
	}
	else
	{
		$status = 'Failed';
	}
	echo json_encode(array('status' => $status));
}
if($action=='closed')
{
	$id = $_GET['id'];
	//$valetname = $_GET['valetname'];
	//$gcm_id = $_GET['gcmid'];
	$update_valet = closed_valetdetails($con,$id);
	if($update_valet)
	{
		$getdata = mysqli_fetch_array($update_valet);
		$mobile = $getdata['mobile'];
		$tokenid = $getdata['tokenid'];
		$msg = "Dear Customer, your Ticket Number $tokenid has been closed.Thank you and visit us soon.";
		$link="http://login.rocktosms.com/api/web2sms.php?workingkey=1499313h210b69009aw9f&sender=VALETT&to=$mobile&message=$msg";
		//echo "<script>alert('".$link."');</script>"; 
		$url = preg_replace("/ /", "%20", $link);
		$homepage = file_get_contents($url);
		$status = 'Success';
	}
	else
	{
		$status = 'Failed';
	}
	echo json_encode(array('status' => $status));
}
if($action=='cancelled')
{
	$id = $_GET['id'];
	//$valetname = $_GET['valetname'];
	//$gcm_id = $_GET['gcmid'];
	$update_valet = cancelled_valetdetails($con,$id);
	if($update_valet)
	{
		$getdata = mysqli_fetch_array($update_valet);
		$mobile = $getdata['mobile'];
		$tokenid = $getdata['tokenid'];
		$msg = "Dear Customer, your Ticket Number $tokenid has been closed.Thank you and visit us soon.";
		$link="http://login.rocktosms.com/api/web2sms.php?workingkey=1499313h210b69009aw9f&sender=VALETT&to=$mobile&message=$msg";
		//echo "<script>alert('".$link."');</script>"; 
		$url = preg_replace("/ /", "%20", $link);
		$homepage = file_get_contents($url);
		$status = 'Success';
	}
	else
	{
		$status = 'Failed';
	}
	echo json_encode(array('status' => $status));
}
?>