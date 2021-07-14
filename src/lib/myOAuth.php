<?php
//header('Content-Type: text/html;charset=UTF-8');
//Config
$AUTHEN_URL					= "https://api.jasmine.com/authen1/oauth/authorize";
$OAUTH_URL					= "https://api.jasmine.com/authen1/oauth/token";
$OAUTH_USER_PROFILE_URL		= 'https://api.jasmine.com/authen1/me';
$OAUTH_GRANT_TYPE			= 'authorization_code';
$host=$_SERVER['HTTP_HOST'];
//echo $host."<BR>";
switch ($host) {
  case 'localhost:8080':
    $OAUTH_CLIENT_ID			= 'jIfaeDsZdg_TestL';
    $OAUTH_CLIENT_SECRET		= 'JIejLlJDuRvRrlovIOsz';
    $REDIRECT_URL				= 'http://localhost:8080/lib/callback.php';
    //ini_set('session.save_path',"tcp://otredis.jasmine.com:7379");
    break;
  case 'app.jasmine.com':
    $OAUTH_CLIENT_ID			= 'CUOhHzcOAb_TISon';
    $OAUTH_CLIENT_SECRET		= 'hdisOCVfRFHLunfsYeCj';
    $REDIRECT_URL				= 'https://app.jasmine.com/tis/lib/callback.php';
    ini_set('session.save_handler','redis');
    ini_set('session.save_path',"tcp://tis-redis:6379"); // jas
    break;
  default:
    $OAUTH_CLIENT_ID='';
    $OAUTH_CLIENT_SECRET='';
    $REDIRECT_URL='';
    break;
}
// Redis Session

//ini_set('session.gc_maxlifetime',28800); // 8 hr. = 28800 sec.
$lifetime=1200; //seconds
session_set_cookie_params($lifetime);
session_start();

function clearSession() {
  $_SESSION = array();
  //unset($_SESSION["isadmin"]);
  session_destroy();
}

function getCurURL() {
  $host=$_SERVER['HTTP_HOST'];
  switch ($host) {
    case 'app.jasmine.com':
      return "https://".$_SERVER['HTTP_HOST']."/tis".$_SERVER['REQUEST_URI'];
      break;
    default:
      return "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
      break;
  }
}

function check_login($code) {
	global $REDIRECT_URL;
	if ($_SESSION["access_token"]=="") {
	// 	if ($_SESSION["refresh_token"]=="") {
	// 		//No session re authen
	// 		if($code=="") {
	// 			OauthAuthen();
	// 		} else {
	// 			$accessToken=requestToken(true,$code);
	// 			header("Location: ".$REDIRECT_URL);
	// 		}
	// 	} else {
    //   //Sign again with refresh token
	// 		$accessToken=requestToken(false,$_SESSION["refresh_token"]);
	// 	}
    // //Init profile login
    // $response=getUserProfile($accessToken);
	// } else {
	// 	$accessToken=$_SESSION["access_token"];
	 }
	 $_SESSION["access_token"]="0e646560-525d-47d9-922e-978d1e160553";
	 $accessToken=$_SESSION["access_token"];
	return $accessToken;
}

function OauthAuthen() {
	global $AUTHEN_URL;
	global $OAUTH_CLIENT_ID;
	global $REDIRECT_URL;
	//header("Location: https://api.jasmine.com/authen1/oauth/authorize?response_type=code&client_id=xx&redirect_uri=http://10.99.161.225/Webservice/callback.php");
	$url=$AUTHEN_URL."?response_type=code&client_id=".$OAUTH_CLIENT_ID."&redirect_uri=".$REDIRECT_URL;
	header("Location: ".$url);
	die();
}

function requestToken($isCode,$code)
{
	global $OAUTH_URL;
	global $OAUTH_GRANT_TYPE;
	global $OAUTH_CLIENT_ID;
	global $REDIRECT_URL;
	global $OAUTH_CLIENT_SECRET;
	if($isCode) {
		$request = array(
			'grant_type'	=> $OAUTH_GRANT_TYPE,
			'client_id'		=> $OAUTH_CLIENT_ID,
			'redirect_uri'	=> $REDIRECT_URL,
			'code'			=> $code
		) ;
	} else {
		$request = array(
			'grant_type'	=> 'refresh_token',
			'client_id'		=> $OAUTH_CLIENT_ID,
			'client_secret'	=> $OAUTH_CLIENT_SECRET,
			'refresh_token'	=> $code
		) ;
	}
	$OAUTH_STR=doString($request);
	//open connection

  $ch = curl_init();
	$header = array("Content-type: application/x-www-form-urlencoded; charset=UTF-8");
	//$header = array("Content-type: application/json; charset=UTF-8");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  curl_setopt($ch, CURLOPT_URL, $OAUTH_URL);
	curl_setopt($ch, CURLOPT_USERPWD, ''.$OAUTH_CLIENT_ID.':'.$OAUTH_CLIENT_SECRET.'');
  curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$OAUTH_STR);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	//execute post
	$response = curl_exec($ch);
  curl_close($ch);

	$data = json_decode($response, true);
	$access_token = $data['access_token'];
	$_SESSION["access_token"]=$data['access_token'];
	$tokenType = $data['token_type'];

	$refreshToken = $data['refresh_token'];
	$_SESSION["refresh_token"]=$refreshToken;
	return $access_token;
}


function getUserProfile($token)
{
	global $OAUTH_USER_PROFILE_URL;
	if($token=="") {
		//ผิดพลาด login ใหม่
		OauthAuthen();
		return;
	}
	$tokenType="bearer";
	$headers = array('Authorization: '.$tokenType.' '.$token.'',
                    'Content-Type: application/json; charset=UTF-8');

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_URL, $OAUTH_USER_PROFILE_URL);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  $response = curl_exec($ch);
  curl_close($ch);

	$userInfo=json_decode($response, true);
	$_SESSION["employee_id"]=$userInfo[0]['employee_id'];
	$_SESSION["thai_fullname"]=$userInfo[0]['thai_fullname'];
	$_SESSION["thai_firstname"]=$userInfo[0]['thai_firstname'];
	$_SESSION["email"]=$userInfo[0]['email'];
	$_SESSION["department"]=$userInfo[0]['department'];
	$_SESSION["company"]=$userInfo[0]['company'];
	return $response;
}

function doString($request)
{
	$last_key=end(array_keys($request));
	foreach($request as $key=>$value)
	{
		if ($key == $last_key)
		{
			$str .= $key.'='.$value.'';
		}
		else
		{
			$str .= $key.'='.$value.'&';
		}

	}
	rtrim($str, '&');
	return $str;
}
?>
