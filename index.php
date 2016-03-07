<?php
session_start();
if (isset($_SESSION['fb_access_token'])): ?>
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
            <input class="button" type="submit" name="searchBooks" value="Search">
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
            include 'php/db_info.php';

            $conn = new mysqli($servername, $username, $password, $dbname);
            $sql = 'SELECT listing.title, listing.listing_id, listing.author, listing.department, listing.class,listing.timestamp_listed,listing.price, user.first FROM listing INNER JOIN user ON listing.seller_id = user.user_id';
            $result = $conn->query($sql);
            $conn->close();

            if ($result->num_rows > 0) {
                echo "<div class='row'>";
                while ($row = $result->fetch_assoc()) {
                    $moneyFormat = money_format('%(#2n', $row['price']);
                    setlocale(LC_MONETARY, 'en_US');
                    
                    $sql =<<<SE
                      SELECT book_id, buyer_id, amount FROM offer WHERE book_id={$row["listing_id"]} AND buyer_id='{$_SESSION["user_id"]}'
SE;
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    $glyph = "glyphicon-send";
                    if ($conn->query($sql) !== false && $conn->query($sql)->num_rows > 0){
                      $glyph = "glyphicon-ok";
                    }
                    $conn->close();
                    
                    echo <<<EOD
                    
                    <div class="col-md-8 col-md-offset-2 listing clearfix">
                      <div class="col-xs-12 mainInfo">
                        <div class="col-xs-5">{$row['title']}</div>
                        <div class="col-xs-4">{$row['author']}</div>
                        <div class="col-xs-2">$ {$moneyFormat}</div>
                        <div class="col-xs-1 tab">
                          <span class="glyphicon {$glyph}" aria-hidden="true" data-book-id="{$row['listing_id']}"></span>
                        </div>
                      </div>
                      <div class="col-xs-12 secondaryInfoHeader">
                        <div class="col-xs-5">Class</div>
                        <div class="col-xs-4">Date Posted</div>
                        <div class="col-xs-3">Seller</div>
                      </div>
                      <div class="col-xs-12 secondaryInfo">
                        <div class="col-xs-5">{$row['department']}-{$row['class']}</div>
                        <div class="col-xs-4">{$row['timestamp_listed']}</div>
                        <div class="col-xs-3">{$row['first']}</div>
                      </div> 
                    </div>
EOD;
                }
            }
            
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

          include 'php/db_info.php';

          $conn = new mysqli($servername, $username, $password, $dbname);
          
          $sql =<<<REQ
          SELECT listing.title, user.user_id, user.first, user.last, user.email FROM listing INNER JOIN user ON listing.seller_id = user.user_id WHERE listing.listing_id = '{$_POST['bookID']}' 
REQ;
          $result = $conn->query($sql);
          $row = $result->fetch_assoc();
          
          $message =<<<MES
            {$_SESSION['user_name']} made an offer for your book with ID = '{$_POST['bookID']}'.

            Their message is:
            '{$_POST['message']}'

            Their offer is:
            ${$_POST['price']}

            <a href="http://tufts-textbook-exchange.com/responce.php?BOOK_ID={$_POST['bookID']}&BUYER_ID={$_SESSION['user_id']}">Click here to accept this offer.</a>
MES;

          $message = wordwrap($message, 70, "\r\n");
          $to =<<<EMA
            '{$row['first']} {$row['last']}' <{$row['email']}>
EMA;

          $subject = 'Someone Made an Offer on your Textbook!';
          $headers = "From: " . "'Tufts Textbook Exchange' <no-reply@tufts-textbook-exchange.com>" . "\r\n";
          $headers .= "MIME-Version: 1.0\r\n";
          $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

          mail($to, $subject, $message, $headers);

          $sql =<<<REQ
            INSERT INTO offer (book_id, buyer_id, amount)
            VALUES ({$_POST['bookID']}, '{$row['user_id']}', {$_POST['price']})
REQ;
          echo $sql;
          $result = $conn->query($sql);
        }
?>
    </div>

  </body>

  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/main.js"></script>
</html>
<?php
else: header("Location: https://". $_SERVER['SERVER_NAME'] . "/login.php");
//echo $_SERVER['SERVER_NAME'];
endif;
?>
