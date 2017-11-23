<?php
include 'config.php';
include 'dbQueries.php';

$companyid = $_GET['companyid'];

$cnt_res = get_counts($con,$companyid);
if($cnt_res)
{
	while($data = mysqli_fetch_array($cnt_res))
	{
		$key = $data['status']; 
		$value = $data['cnt'];
		$vehicle[$key] = $value ;
	}
}

$drivers = select_valetscounts($con,$companyid);
$driver_cnt = mysqli_num_rows($drivers);
$vehicle['drivers'] = $driver_cnt;
//print_r($vehicle);

$assign_cnt = get_assigncounts($con,$companyid);
$driver_res = mysqli_fetch_array($assign_cnt);
$driver_assigncnt = $driver_res['cnt'];
$vehicle['assign'] = $driver_assigncnt;
$vehicle['readytoassign'] = $driver_cnt - $driver_assigncnt;

//$status = 'waiting';
$vehicles = select_allvaletdetails($con,$companyid);
$vehicle_cnt = mysqli_num_rows($vehicles);
$vehicle['vehicle_parked'] = $vehicle_cnt;

echo json_encode(array('result'=>$vehicle,'status' => 'success'));
/*if($vres)
{		
	echo json_encode(array('status' => 'Success'));		
}
else
{
	echo json_encode(array('status' => 'Failed'));
}*/
?>