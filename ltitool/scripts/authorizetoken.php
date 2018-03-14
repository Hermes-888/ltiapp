<?php
/* Request token for api calls
   https://canvas.instructure.com/doc/api/file.oauth.html
   https://developer.wordpress.com/docs/oauth2/
   https://canvas.instructure.com/doc/api/file.oauth.html#using-refresh-tokens
        
works best when logged into canvas.

{"access_token":"14~yS7cXhAlptprxk3oXTQbzp9DT87bFCQ0VdbiDRxUgXHOnXWwN3qAd7A4YUV0W1hU",
 "user":{"id":1568377,"name":"Travis Jones"},
 "refresh_token":"14~I9MGSps2RGygMtV4hxegOHSDi17Lou5sREQ4nDcfJci5LR0r3X5odZHDqW7dLLJd"}
 1

    first pass 'code' is not set
*/
//header('Access-Control-Allow-Origin: *');//https://uvu.instructure.com
//header('Access-Control-Allow-Methods:GET, POST, OPTIONS');// might not need this

if (isset($_GET['code'])) {
    // try to get an access token
    $state = $_GET['state'];
    $code = $_GET['code'];
    
    $url = 'https://uvu.instructure.com/login/oauth2/token';
    $params = array(
        "code" => $code,
        "client_id" => "140000000000087",
        "client_secret" => "WK1o5GVOueM1Ha8qDgnQFfFhTXkBMomScDSY0v6Itlr466DnNcJ4CFNjasAMb2Qa",
        "redirect_uri" => 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"],
        "grant_type" => "authorization_code"
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    
    $info = curl_getinfo($ch);
    curl_close($ch);
    
    if ($info['http_code'] === 200) {
        header('Content-Type: ' . $info['content_type']);
        //echo $output;// to screen

        $auth = json_decode($output, true);
        $access = $auth['access_token'];
        //echo '<br><br>acc: '.$access;
        //$rtoken = $auth['refresh_token'];
        //echo '<br><br>ref: '.$rtoken;
        //$user = $auth['user'];// {id,name}
        //echo '<br><br>usr: '.$user;
        
        /* Store in consumers[1]token */
        require_once('connect.php');
        $id = 1;
        try {
            $conn = new PDO($dsna, $dbuser, $dbpass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    

            $stmt = $conn->prepare("UPDATE consumers SET token=:access WHERE id=:id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':access', $access, PDO::PARAM_STR);
            $stmt->execute();
            echo "stored";
            $status='stored';

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
            $status='ERROR: ' . $e->getMessage();
        }
?>
    <script>
        // dispatch event back to viewInstructor
        var access='<?php echo $status; ?>';
        window.parent.postMessage({'access':access},'*');
    </script>
<?php
        
    } else {
        echo 'An error happened: '.$info['http_code'];
    }
    
    if(isset($_GET['error'])) { echo 'ERR:' . $_GET['error']; }
    
} else {
  
    //first pass 'code' is not set
    $url = "https://uvu.instructure.com/login/oauth2/auth";

    $params = array(
        "client_id" => "140000000000087",
        "response_type" => "code",
        "redirect_uri" => 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"],
        "purpose" => "Update_Media",
        "state" => "VuV"
    );

    $request_to = $url . '?' . http_build_query($params);

    header("Location: " . $request_to);// CORS error
}
