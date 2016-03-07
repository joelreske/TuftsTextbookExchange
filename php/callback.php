<?php
    session_start();

    require "php/facebook.php";

    $helper = $fb->getRedirectLoginHelper();

    try {
        $accessToken = $helper->getAccessToken();
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
      echo 'Graph returned an error: '.$e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        var_dump($helper->getError());
        echo 'Facebook SDK returned an error: '.$e->getMessage();
        exit;
    }

    if (!isset($accessToken)) {
        if ($helper->getError()) {
            header('HTTP/1.0 401 Unauthorized');
            echo 'Error: '.$helper->getError()."\n";
            echo 'Error Code: '.$helper->getErrorCode()."\n";
            echo 'Error Reason: '.$helper->getErrorReason()."\n";
            echo 'Error Description: '.$helper->getErrorDescription()."\n";
        } else {
            header('HTTP/1.0 400 Bad Request');
            echo 'Bad request';
        }
        exit;
    }

    $oAuth2Client = $fb->getOAuth2Client();
    $tokenMetadata = $oAuth2Client->debugToken($accessToken);
    $tokenMetadata->validateAppId('1012035298840459');
    $tokenMetadata->validateExpiration();

    if (!$accessToken->isLongLived()) {
        // Exchanges a short-lived access token for a long-lived one
      try {
          $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
      } catch (Facebook\Exceptions\FacebookSDKException $e) {
          echo '<p>Error getting long-lived access token: '.$helper->getMessage()."</p>\n\n";
          exit;
      }
    }

    try {

    // Returns a `Facebook\FacebookResponse` object
    $response = $fb->get('/me?fields=id,name,first_name,last_name,picture.width(400).height(400)', $accessToken);
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: '.$e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: '.$e->getMessage();
        exit;
    }

    $body = $response->getDecodedBody();
    $user = $response->getGraphUser();

    $_SESSION['user_name'] = $body['name'];
    $_SESSION['user_id'] = $body['id'];
    $_SESSION['user_img'] = $body['picture']['data']['url'];

    $_SESSION['fb_access_token'] = (string) $accessToken;

    include '/php/db_info.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = <<<EOD
        INSERT IGNORE INTO user (user_id, first, last)
        VALUES ('{$user->getId()}', '{$user->getFirstName()}', '{$user->getLastName()}')
EOD;
    $conn->query($sql);

    $sql = <<<EOD
        SELECT email, phone, timestamp_joined FROM user WHERE user_id = '{$user->getId()}'
EOD;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $_SESSION['timestamp_joined'] = $row['timestamp_joined'];
    if ($row['email']){
        $_SESSION['user_email'] = $row['email'];
    }
    if ($row['phone']){
        $_SESSION['user_phone'] = $row['phone'];
    }
    

    header("Location: https://". $_SERVER['SERVER_NAME'] . "./index.php");
    $conn->close();
?>
