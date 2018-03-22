<?php
/*
	generate consumer key & secret
	https://thomashunter.name/blog/generate-oauth-consumer-key-and-shared-secrets-using-php/
	https://toys.lerdorf.com/archives/55-Writing-an-OAuth-Provider-Service.html
*/
	$nukey = gen_oauth_creds();
	
	$result='{"data":[{';// json object
	$result .='"consumer_key":"'.$nukey['consumer_key'].'",';
	$result .='"shared_secret":"'.$nukey['shared_secret'].'"}]}';
	
	echo $result;
	//echo $nukey['consumer_key'].'<br>';
	//echo $nukey['shared_secret'];

function gen_oauth_creds() {
	// Get a whole bunch of random characters from the OS
	//$fp = fopen('/dev/urandom','rb');
	$entropy = random_bytes(60);//int(32323, 10000);// php7//fread($fp, 32);
	//fclose($fp);

	// Takes our binary entropy, and concatenates a string which represents the current time to the microsecond
	$entropy .= uniqid(mt_rand(), true);

	// Hash the binary entropy
	$hash = hash('sha512', $entropy);

	// Base62 Encode the hash, resulting in an 86 or 85 character string
	$hash = gmp_strval(gmp_init($hash, 16), 62);

	// Chop and send the first 80 characters back to the client
	return array(
		'consumer_key' => substr($hash, 0, 32),
		'shared_secret' => substr($hash, 32, 48)
	);
}
?>