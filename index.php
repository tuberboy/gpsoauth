<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Tuber Boy">
    <title>Google Play Services OAuth Master Token Generator - TuberBoy.Com</title>
    <meta name="msapplication-TileColor" content="#786fff">
    <meta name="theme-color" content="#786fff">
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<div class="header"><a href="">GPSoAuth - (TUBER BOY)</a></div>
    <div class="box">
    <div class="title">MASTER TOKEN GENERATOR</div>
    <div class="box">At first login gmail in any browser and click <b>Continue</b> by visiting this URL: <a href="https://accounts.google.com/DisplayUnlockCaptcha" target="_blank">Display Unlock Captcha</a></div>
    <form method="post">
        <input type="email" name="gmail" placeholder="Enter A Gmail/E-mail Address *" autocomplete="off" required>
        <input type="text" name="gpass" placeholder="Enter Gmail/E-mail Password *" autocomplete="off" required>
        <button name="get">GET TOKEN</button>
    </form>
    </div>

<?php
set_time_limit(0);
require 'EncryptedPasswd/EncryptedPasswd.php';

if(isset($_POST['get']))
{
    $gmailAddress = $_POST['gmail'];
    $gmailPassword = $_POST['gpass'];
    
    $masterToken = getMasterTokenForAccount($gmailAddress, $gmailPassword);
    echo $masterToken;
}

function getMasterTokenForAccount($email, $password) 
{
    $url = 'https://android.clients.google.com/auth';

    $data = array(
		'accountType' => 'HOSTED_OR_GOOGLE',
		'Email' => $email,
		'google_play_services_version' => '212418005',
		'system_partition' => 1,
		'has_permission' => 1,
		'add_account' => 1,
		'oauth2_foreground' => 1,
		'check_email' => 1,
		'EncryptedPasswd' => EncryptedPasswd($email, $password),
		'service' => 'ac2dm',
		'source' => 'android',
		'androidId' => '3c4b99e2bb1e22d2',
		'device_country' => 'us',
		'operatorCountry' => 'us',
		'lang' => 'en_US',
		'sdk_version' => 20,
		'app' => 'com.google.android.gms',
		'client_sig' => '38918a453d07199354f8b19af05ec6562ced5788',
		'callerSig' => '38918a453d07199354f8b19af05ec6562ced5788'
	);

    $options = array(
        'ssl' => array(
            'ciphers' => 'ECDHE+AESGCM:ECDHE+CHACHA20:DHE+AESGCM:DHE+CHACHA20:ECDH+AES:DH+AES:RSA+AESGCM:RSA+AES:!aNULL:!eNULL:!MD5:!DSS',
			'verify_peer' => true, 
            'verify_peer_name' => false,
            'SNI_enabled' => true
		),
			
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\nConnection: close\r\nUser-Agent: GoogleAuth/1.4 (scx35_sp7731gea KOT49H)",
            'method' => 'POST',
            'content' => http_build_query($data),
            'ignore_errors' => TRUE,
            'protocol_version'=>'1.1'
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if (strpos($http_response_header[0], '200 OK') === false) 
    {
        return '<div class="box"><div class="title">ERROR: WRONG EMAIL or PASSWORD</div>Please check your email and password, is that correct or not.<b>Or,</b> Login your gmail account using any web browser and click <b>Continue</b> button by visiting this url: <a href="https://accounts.google.com/DisplayUnlockCaptcha" target="_blank">Display Unlock Captcha</a><br>After that try again. Thanks</div>';
    }

    $startsAt = strpos($result, "Token=") + strlen("Token=");
    $endsAt = strpos($result, "\n", $startsAt);
    $token = substr($result, $startsAt, $endsAt - $startsAt);
    if(empty($token))
    {
        return '<div class="box"><div class="title">ERROR: FOLLOW INSTRACTIONS</div>Login your gmail account using any web browser and click <b>Continue</b> button by visiting this url: <a href="https://accounts.google.com/DisplayUnlockCaptcha" target="_blank">Display Unlock Captcha</a><br>After that try again. Thanks</div>';
    } else {
        return '<div class="box"><div class="title">MASTER TOKEN</div><textarea type="text">'.$token.'</textarea></div><div class="box"><div class="title">JSON DATA</div><textarea type="text">'.$result.'</textarea></div>';
    }
}
?>
<div class="footer">&copy; Copyright <?php echo date('Y'); ?> - <a href="https://github.com/tuberboy">Tuber Boy</a> || <a href="https://tuberboy.com">TuberBoy.Com</a></div>
</body>
</html>