<?php
function sendMessage($data,$target)
{
	//FCM api URL
	$url = 'https://fcm.googleapis.com/fcm/send';
	//api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
	$server_key = 'AAAA4YKCIEI:APA91bGXMmaWGcVbMdSAqWztF5hH4sk0jsYn8dqnudU-ks-3OSG9rRjwC1kCtyfQ11wrwugoKXrdcwM3PqnqnpHIqAS5BGiekZlyGpgRxz5O85D_2Qyrzb2fS9s-_fS7qSx6Vn6qe6g_';
				
	$fields = array();
	$fields['data'] = $data;
	if(is_array($target))
	{
		$fields['registration_ids'] = $target;
	}
	else
	{
		$fields['to'] = $target;
	}
	//header with content_type api key
	$headers = array(
		'Content-Type:application/json',
	  'Authorization:key='.$server_key
	);
				
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);
	if ($result === FALSE) 
	{
		die('FCM Send Error: ' . curl_error($ch));
	}
	curl_close($ch);
	return $result;
}

/*$data =  json_decode('{"no_of_results":1,"result":{"role":"driver","companyid":"3","name":"xyz2","mobile":"9550548432","username":"test2","emptype":null,"id":"5","event":"valet","shorttext":"valetdetails"},"status":"Success"}', true); //array ("message" => 'hi',"text"=>'hello');
$target = "eKkn1Tpbijw:APA91bE0_Kq9i_mVPxvTc6kNSkflP-SjmT0qPO2Sx5tq5B6zmcKKuSmx8veeZQoqXHB3dR2zsky2bXXGlt0LdmBeZxSzFrkgBOx6Yh0fLRmtfdc5Gi4XUOSSsTC2D160fu3KQkq5lnNk";
$res = sendMessage($data,$target);
print_r($res);*/
?>