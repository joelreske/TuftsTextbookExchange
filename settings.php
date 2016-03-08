<?php
    require_once __DIR__.'/vendor/autoload.php';

    $DEV = true;

    $_SESSION['base_url'] = "http://tufts-textbook-exchange.com/";

    if($DEV){ 
        $_SESSION['base_url'] = "http://tufts-textbook-exchange.com/dev/";
    }

    $fb = new Facebook\Facebook([
      'app_id' => '1012035298840459',
      'app_secret' => 'bf38d8acb3dc6db531b292d7080c6cad',
      'default_graph_version' => 'v2.4',
      ]);
?>
