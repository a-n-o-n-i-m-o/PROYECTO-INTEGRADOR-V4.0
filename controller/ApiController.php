<?php

$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InBveWlzMTM0NDhAZ2lhbmVzLmNvbSJ9.ZV0XdT0SLeNjsfHRsHc3hzQbR1NlWl4MXr_G3kim0YA';
$dni = $_POST["dni"]; 

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://dniruc.apisperu.com/api/v1/dni/' . $dni . '?token=' . $token,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 2,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);

if ($response) {
    //$persona = json_decode($response);
    //var_dump($persona);
    $msg = array(
        "tipo"  => "success",
        "data" => $response
    );
} else {
    $msg = array(
        "tipo"  => "error",
        "data" => "not found"
    );
}
    echo json_encode($msg);
        return;
?>