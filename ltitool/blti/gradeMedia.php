<?php
require_once('OAuthBody.php');
require_once '../scripts/connect.php';
/*
	called from Flash media or viewStudent.php
	post required data to this file
	use key to look up secret in consumers table
	duh flash post:
	urlvars.surl=surl;
	urlvars.srcid=srcid;
	urlvars.skey = skey;
	urlvars.score=score;
*/	
	$score = $_POST['score'];// sent from media content
	$sourcedid = $_POST['srcid'];//sourcedid'];//Canvas: lis_result_sourcedid
	$endpoint = $_POST['surl'];//Canvas: ext_ims_lis_basic_outcome_url
	$key = $_POST['skey'];//Canvas: oauth_consumer_key
	$secret = "";//consumers['secret_column']
	//read record in consumers table for key to get secret
	
	try {
		
		$conn = new PDO($dsna, $dbuser, $dbpass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//$stmt = $conn->prepare('SELECT * FROM consumers WHERE key_column = :key');
		$stmt = $conn->prepare('SELECT secret_column FROM consumers WHERE key_column = :key');
		$stmt->bindValue(':key', $key, PDO::PARAM_STR);
		$stmt->execute();
		
		$result = $stmt->fetchAll();
		
		if ( $stmt->rowCount() != 1 ) {
			echo "Your consumer is not authorized consumer_key";
			return;
		} else {
			foreach($result as $row) { 
				$secret = $row['secret_column'];
				break;
			}
			if ( ! is_string($secret) ) {
				echo "Could not retrieve consumer";
				return;
			}  
		}

		//create XML body to set grade
		$body = '<?xml version = "1.0" encoding = "UTF-8"?>
		<imsx_POXEnvelopeRequest xmlns="http://www.imsglobal.org/services/ltiv1p1/xsd/imsoms_v1p0">
		  <imsx_POXHeader>
			<imsx_POXRequestHeaderInfo>
			  <imsx_version>V1.0</imsx_version>
			  <imsx_messageIdentifier>999999123</imsx_messageIdentifier>
			</imsx_POXRequestHeaderInfo>
		  </imsx_POXHeader>
		  <imsx_POXBody>
			<replaceResultRequest>
			  <resultRecord>
				<sourcedGUID>
				  <sourcedId>' . $sourcedid . '</sourcedId>
				</sourcedGUID>
				<result>
				  <resultScore>
					<language>en</language>
					<textString>'.$score.'</textString>
				  </resultScore>
				</result>
			  </resultRecord>
			</replaceResultRequest>
		  </imsx_POXBody>
		</imsx_POXEnvelopeRequest>';
		
		//Set variables to be used in the sendOAuthBodyPOST function
		$method = 'POST';
		//$oauth_consumer_key = $key;
		//$oauth_consumer_secret = $secret;
		$content_type = 'application/xml';
		$sendOAuthBodyPOST = sendOAuthBodyPOST($method, $endpoint, $key, $secret, $content_type, $body);
	
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}
	$conn = null;// empty connection	
?>