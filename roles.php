<?php
include 'config.php';
include 'dbQueries.php';
$res = get_roles($con);
if(mysqli_num_rows($res)!=0) 
{
	while($data = mysqli_fetch_assoc($res)) 
	{ 
        $result = $data['role']; 			
		$roles[] = $result ;
	}
}

header('Content-type: application/json');

echo json_encode(array('no_of_results' => count($roles),'result'=>$roles,'status' => 'success'));
?>