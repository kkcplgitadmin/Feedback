<?php
include "config.php";
function get_roles($con)
{
	$roles = "select * from roles";
	$res = mysqli_query($con,$roles);
	return $res;
}

function insert_companylogin($con,$name,$user,$pwd,$address,$mobile,$regno,$alternativeno,$image)
{
	global $now;
	$ins = "insert into company_logins (company_name,company_user,company_pwd,address,mobile,regno,alternativeno,image,created_timestamp) values ('$name','$user','$pwd','$address','$mobile','$regno','$alternativeno','$image','$now')";
	$res = mysqli_query($con,$ins);
	$sel = mysqli_query($con,"select id from company_logins where company_user='$user' and company_pwd='$pwd'");
	$res = mysqli_fetch_array($sel);
	return $res;
}

function insert_userlogin($con,$companyid,$name,$user,$pwd,$mobile,$role,$emptype)
{
	global $now;
	$ins = "insert into user_logins (companyid,name,mobile,username,password,role,emptype,inserted_timestamp) values ('$companyid','$name','$mobile','$user','$pwd','$role','$emptype','$now')";
	$res = mysqli_query($con,$ins);
	return $res;
}

function insert_feedback($con,$name,$mobile,$email,$dob,$description,$tokenid,$companyid,$food,$service,$order_service_time,$porter_name,$porter_rating)
{
	global $now;
	$ins = "insert into feedback_details (name,mobile,email,dob,description,tokenid,companyid,food,service,order_service_time,porter_name,porter_rating,inserted_timestamp) values ('$name','$mobile','$email','$dob','$description','$tokenid','$companyid','$food','$service','$order_service_time','$porter_name','$porter_rating','$now')";
	$res = mysqli_query($con,$ins);
	return $res;
}

function insert_valet($con,$name,$mobile,$email,$dob,$description,$tokenid,$companyid,$porter_name)
{
	//global $now;
	$ins = "update valet_details set name='$name',email='$email',dob='$dob',description='$description',porterid='$porter_name',status='Get Vehicle' where tokenid='$tokenid'";
	//echo $ins;
	$res = mysqli_query($con,$ins);
	return $res;
}

function select_userlogins($con,$username,$password)
{
	$sel_qry = "select * from user_logins where username='$username' and password='$password'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function select_valetdetails($con,$companyid,$status)
{
	if($status=='Parked')
	{
		$sel_qry = "select * from valet_details where companyid='$companyid' and status in ('$status','Get Vehicle')";
	}
	else
	{
		$sel_qry = "select * from valet_details where companyid='$companyid' and status = '$status'";//in ('waiting','Assigned')";// AND tokenid!=''
	}
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

/*function select_parkedvehicles($con,$companyid,$status)
{
	$sel_qry = "SELECT * FROM vehicle_details v WHERE tokenid NOT IN (SELECT tokenid FROM valet_details WHERE companyid='$companyid') ";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}*/

function select_valets($con,$companyid)
{
	$sel_qry = "select * from user_logins where companyid='$companyid' and role='driver'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function select_valetscounts($con,$companyid)
{
	$sel_qry = "select * from user_logins where companyid='$companyid' and role='driver' and status='ACTIVE'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function select_availabledrivers($con,$companyid)
{
	$sel_qry = "SELECT u.name,u.username,u.companyid,u.id,u.emptype,u.mobile FROM user_logins u WHERE u.name NOT IN (SELECT assign FROM valet_details WHERE STATUS='Delivery Due') AND u.role='driver' AND u.companyid='$companyid'  and u.status='ACTIVE'";
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function select_allvaletdetails($con,$companyid)
{
	$sel_qry = "select * from valet_details where companyid='$companyid' ";// and status='Processed'";//AND tokenid!=''
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function update_valetdetails($con,$id,$valetname)
{
	$sel_qry = "update valet_details set assign='$valetname',status='Delivery Due' where id='$id'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function update_vehiclestatus($con,$id,$valetname)
{
	$sel_qry = "update valet_details set assign='$valetname',status='Delivery Due' where id='$id'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function check_userlogins($con,$username)//,$mobile
{
	$sel_qry = "select * from user_logins where username='$username' ";//or mobile='$mobile'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function insert_vehicledetails($con,$vehicleno,$mobile,$tokenid,$color,$subvehicleno,$companyid)
{
	global $now;
	$ins = "insert into valet_details(mobile,vehicleno,color,tokenid,subvehicleno,inserted_timestamp,companyid) values ('$mobile','$vehicleno','$color','$tokenid','$subvehicleno','$now','$companyid')";
	//echo $ins;
	$res = mysqli_query($con,$ins);
	return $res;
}

function select_vehicledetails($con,$mobileno)
{
	$sel_qry = "select * from valet_details where mobileno='$mobileno'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function select_allvehicledetails($con)
{
	$sel_qry = "select * from valet_details where status not in ('Delivered','Cancelled')";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function select_vehicledetailsbytokenid($con,$tokenid)
{
	$sel_qry = "select * from valet_details where tokenid='$tokenid'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function gcmiddetails($con,$regid,$username)
{
	$sel_qry = "select * from gcm_regids where username='$username'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	$rows = mysqli_num_rows($res);
	if($rows!='0')
	{
		$update_qry = "update gcm_regids set gcmid='$regid' where username='$username'";
		//echo $update_qry;
		$res = mysqli_query($con,$update_qry);
	}
	else
	{
		$ins_qry = "insert into gcm_regids(username,gcmid) values ('$username','$regid')";
		//echo $ins_qry;
		$res = mysqli_query($con,$ins_qry);
	}
	return $res;
}

function select_gcmid($con,$valetname)
{
	$sel_qry = "select * from gcm_regids where username='$valetname'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function select_uid($con)
{
	$sel_qry = "select * from username";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function update_uid($con)
{
	$update_qry = "update username set uid=uid+1";
	//echo $update_qry;
	$res = mysqli_query($con,$update_qry);
	return $res;
}

function select_valetbyid($con,$valetname)
{
	$sel_qry = "select * from valet_details where assign='$valetname'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function select_getpassword($con,$username)
{
	$sel_qry = "select * from user_logins where username='$username'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function update_userdata($con,$username,$password)
{
	$sel_qry = "update user_logins set password='$password' where username='$username'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function ack_valetdetails($con,$id)
{
	$sel_qry = "update valet_details set ackstatus='true' where id='$id'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	if($res)
	{
		$sel_qry = "select * from valet_details where id='$id'";
		//echo $sel_qry;
		$res = mysqli_query($con,$sel_qry);
	}
	return $res;
}

function closed_valetdetails($con,$id)
{
	$sel_qry = "update valet_details set status='Delivered' where id='$id'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	if($res)
	{
		$sel_qry = "select * from valet_details where id='$id'";
		//echo $sel_qry;
		$res = mysqli_query($con,$sel_qry);
	}
	return $res;
}

function cancelled_valetdetails($con,$id)
{
	$sel_qry = "update valet_details set status='Cancelled' where id='$id'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	if($res)
	{
		$sel_qry = "select * from valet_details where id='$id'";
		//echo $sel_qry;
		$res = mysqli_query($con,$sel_qry);
	}
	return $res;
}

function update_profiledetails($con,$id,$mobile,$name,$emptype,$status)
{
	$sel_qry = "update user_logins set mobile='$mobile',name='$name',emptype='$emptype',status='$status' where id='$id'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function get_valetadmin($con,$companyid)
{
	$sel_qry = "select * from user_logins where companyid='$companyid' and role='valetadmin'";
	//echo $sel_qry;
	$res = mysqli_query($con,$sel_qry);
	return $res;
}

function update_vehicledetails($con,$vehicleno,$mobile,$tokenid,$color,$id,$subvehicleno)
{
	$vehicle_qry = "update valet_details set mobile='$mobile',vehicleno='$vehicleno',color='$color',tokenid='$tokenid',subvehicleno='$subvehicleno' where id='$id'";
	//echo $vehicle_qry;
	$res = mysqli_query($con,$vehicle_qry);
	return $res;
}

function get_counts($con,$companyid)
{
	$count_qry = "select distinct(status),count(*) as cnt from valet_details where companyid='$companyid' group by 1";
	//echo $count_qry;
	$res = mysqli_query($con,$count_qry);
	return $res;
}

function get_assigncounts($con,$companyid)
{
	$count_qry = "SELECT COUNT(*) AS cnt FROM valet_details WHERE companyid='$companyid' AND STATUS='Delivery Due'";
	//echo $count_qry;
	$res = mysqli_query($con,$count_qry);
	return $res;
}
?>