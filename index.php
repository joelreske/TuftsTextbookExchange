<?php
session_start();

if (isset($_SESSION['fb_access_token'])):

require "settings.php";
require 'search.php';
require 'php/make_offer.php';

  ?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tufts Textbook Exchange | Home</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <script src="js/jquery-2.1.4.min.js"></script>

  </head>
  <body>

    <div class="container-fluid">

      <?php include 'php/header.php'; ?>

      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h4>Search for Books:</h4>
        </div>
      </div>

      <div class="row">
        <form id="searchForm" action="" method="post" class="col-md-8 col-md-offset-2 form">

          <div class="col-md-5 col-md-offset-1">
            <div class="col-md-4">Title:</div>
            <div class="col-md-8"><input type="text" name="title" placeholder="Moby Dick"></div>
          </div>

          <div class="col-md-5 col-md-offset-1">
            <div class="col-md-4">Author:</div>
            <div class="col-md-8"><input type="text" name="author" placeholder="Herman Melville"></div>
          </div>

          <div class="col-md-5 col-md-offset-1">
            <div class="col-md-4">Department:</div>
            <div class="col-md-8"><input type="text" name="department" placeholder="COMP"></div>
          </div>

          <div class="col-md-5 col-md-offset-1">
            <div class="col-md-4">Class:</div>
            <div class="col-md-8"><input type="text" name="class"  placeholder="0040"></div>
          </div>

          <div class="col-md-4 col-md-offset-4">
            <input class="button" type="submit" name="" value="Search">
          </div>
        </form>
      </div>

      <span id="recentListings">
        <div class="row">
          <div class="col-md-8 col-md-offset-2">
            <h4>Recently Sold:</h4>
          </div>
        </div>

        <div class="row">
          <div class="col-md-8 col-md-offset-2 listing ">
            <div class="col-xs-12 heading">
              <div id="title" class="col-xs-5">Title</div>
              <div id="author" class="col-xs-4">Author</div>
              <div id="price"class="col-xs-3">Price</div>
            </div>
          </div>
        </div>

        <?php
          $search = true;
          
          $title = $_POST['title'];
          $author = $_POST['author'];
          $dep = $_POST['department'];
          $class = $_POST['class'];

          if($title.$author.$dep.$class == ""){
            $search = false;
          }

          /*echo "Searching... \n title: {$title} \n author: {$author} \n" .
                               "department: {$dep} \n class: {$class} \n";
          */

          displayListings($search, $title, $author, $dep, $class); 

        ?>
      </span>
      
      <div class="row" id="offerContainer">
          <form id="makeOffer" action="" method="post" class="col-md-8 col-md-offset-2 form offer">
            <div class="col-md-10 col-md-offset-1">
              <div class="col-md-3">Offer Price:</div>
              <div class="col-md-5" style="padding:0;">
                <div class="col-xs-2">$</div>
                <div class="col-xs-10">
                  <input type="number" name="price" placeholder="50.00" style="display:inline-block;">
                </div>
              </div>
            </div>
            <input id="bookID" type="text" name="bookID"  style="display:none;">
            <div class="col-md-10 col-md-offset-1">
              <div class="col-md-3">Cutom Message:</div>
              <div class="col-md-7"><textarea name="message" placeholder="Type your message to the seller here..." style="width:100%; height: 200px; padding:10px;font-size:15px;"></textarea></div>
            </div>

            <div class="col-md-4 col-md-offset-4">
              <input id="offerSubmit" class="button" type="submit" name="makeOffer" value="Make Offer">
            </div>
          </form>
      </div>

      <?php 
        if(isset($_POST['makeOffer'])){
          make_offer();
        }
      ?>

    </div>

  </body>
  <?php include "php/footer.php" ?>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
</html>
<?php

else: header("Location: " . $_SESSION['base_url'] . "login.php");
endif;
?>
