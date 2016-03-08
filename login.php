<?php
  session_start();

  require "facebook.php";

  $helper = $fb->getRedirectLoginHelper();

  $permissions = ['email']; // Optional permissions
  $loginUrl = $helper->getLoginUrl('https://tufts-textbook-exchange.com/callback.php', $permissions);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Tufts Textbook Exchange | Login</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/index.css">
  <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
  <div class="row">
    <div id="login-container" class="col-xs-10 col-xs-offset-1 clearfix">
      <div id="login-button" class="col-md-6 col-md-offset-3">
        <div class="col-md-10 col-md-offset-1">
          <h2>Join the Tufts Textbook Exchange to buy and sell your textbooks TEST</h2>
        </div>
        <div class="col-md-10 col-md-offset-1">
          <?php echo '<a href="'.htmlspecialchars($loginUrl).'" class="button">Log in with Facebook!</a>'; ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>





  
