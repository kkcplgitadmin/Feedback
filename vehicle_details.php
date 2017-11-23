<?php
include 'config.php';
include 'dbQueries.php';

$action = $_GET['action'];

if($action == 'insert')
{
	$vehicleno = $_GET['vehicleno'];
	$subvehicleno = $_GET['subvehicleno'];
	$mobile = $_GET['mobile'];
	$color = $_GET['color'];
	$tokenid = $_GET['tokenid'];
	$companyid = $_GET['companyid'];
	/*$tokenres = select_tokenid($con);
	$getdata = mysqli_fetch_array($tokenres);
	$tokenid = $getdata['tokenid'];*/

	//$len = strlen($vehicleno);
	//$tokenid = substr($vehicleno, -4).rand(1000,99999);

	$vres = insert_vehicledetails($con,$vehicleno,$mobile,$tokenid,$color,$subvehicleno,$companyid);
	if($vres)
	{		
		$msg = "Dear Customer, your vehicle has been successfully registered with us, your token number is $tokenid for Vehicle Number $vehicleno$subvehicleno.";
		$link="http://login.rocktosms.com/api/web2sms.php?workingkey=1499313h210b69009aw9f&sender=VALETT&to=$mobile&message=$msg";
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
	$mobileno = $_GET['mobile'];
	$fbres = select_vehicledetails($con,$mobileno);
	if(mysqli_num_rows($fbres)!=0) 
	{
		//$getdata = mysqli_fetch_assoc($fbres);
		//print_r($getdata);

		while($getdata = mysqli_fetch_assoc($fbres)) 
		{  
			$result['tokenid'] = $getdata['tokenid']; 
			$result['mobile'] = $getdata['mobile'];
			$result['color'] = $getdata['color'];
			$result['vehicleno'] = $getdata['vehicleno'];
			$result['subvehicleno'] = $getdata['subvehicleno'];
			$result['time'] = $getdata['inserted_timestamp'];
			$result['id'] = $getdata['id'];
			$vehicle[] = $result ;
		}
		//print_r($vehicle);
	}
	echo json_encode(array('no_of_results' => count($vehicle),'result'=>$vehicle,'status' => 'success'));
}
if($action == 'getall')
{
	$fbres = select_allvehicledetails($con);
	if(mysqli_num_rows($fbres)!=0) 
	{
		//$getdata = mysqli_fetch_assoc($fbres);
		//print_r($getdata);

		while($getdata = mysqli_fetch_assoc($fbres)) 
		{  
			$result['tokenid'] = $getdata['tokenid']; 
			$result['mobile'] = $getdata['mobile'];
			$result['color'] = $getdata['color'];
			$result['vehicleno'] = $getdata['vehicleno'];
			$result['subvehicleno'] = $getdata['subvehicleno'];
			$result['time'] = $getdata['inserted_timestamp'];
			$result['id'] = $getdata['id'];
			$vehicle[] = $result ;
		}
		//print_r($vehicle);
	}
	echo json_encode(array('no_of_results' => count($vehicle),'result'=>$vehicle,'status' => 'success'));
}

if($action == 'update')
{
	$vehicleno = $_GET['vehicleno'];
	$subvehicleno = $_GET['subvehicleno'];
	$mobile = $_GET['mobile'];
	$color = $_GET['color'];
	$tokenid = $_GET['tokenid'];
	$id = $_GET['id'];

	$vres = update_vehicledetails($con,$vehicleno,$mobile,$tokenid,$color,$id,$subvehicleno);
	if($vres)
	{		
		echo json_encode(array('status' => 'Success'));		
	}
	else
	{
		echo json_encode(array('status' => 'Failed'));
	}
}

if($action=='assign')
{	
	$id = $_GET['id'];
	$valetname = $_GET['valetname'];
	//$gcm_id = $_GET['gcmid'];
	$getdetails = update_vehiclestatus($con,$id,$valetname);
	//print_r($getdetails);
	if($getdetails) 
	{
		echo json_encode(array('status' => 'success'));
	}
	else
	{
		echo json_encode(array('status' => 'Failed'));
	}
}
?>