<?php 

session_start();

function displayListings($search, $title, $author, $dep, $class){
  if($search){
    displaySearchedListings($title, $author, $dep, $class);
  }else{
    displayAllListings();
  }
}

function displaySearchedListings($title, $author, $dep, $class){
  require 'php/db_info.php';
  require "settings.php";

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  //echo "Searching for: '" . $searchString . "' ";

  if($title == ""){$title = "qqqqqqq";} 
  if($author == ""){$author = "qqqqqqq";} 
  if($dep == ""){$dep = "qqqqqqq";} 
  if($class == ""){$class = "qqqqqqq";} 

  $sql = <<<EOD
  SELECT listing.title, listing.author, listing.department, listing.class, listing.timestamp_listed, listing.price, user.first FROM listing INNER JOIN user ON listing.seller_id = user.user_id
  WHERE listing.title LIKE '%$title%' 
      OR listing.author LIKE '%$author%' 
      OR listing.department LIKE '%$dep%' 
      OR listing.class LIKE '%$class%'
  ORDER BY CASE 
        WHEN listing.title = '$title' THEN 0
        WHEN listing.title LIKE '$title%' THEN 1
        WHEN listing.title LIKE '%$title%' THEN 2
        WHEN listing.title LIKE '%$title' THEN 3
        WHEN listing.author = '$author' THEN 0
        WHEN listing.author LIKE '%$author' THEN 1
        WHEN listing.author LIKE '%$author%' THEN 2
        WHEN listing.author LIKE '%$author' THEN 3
        WHEN listing.department = '$dep' THEN 0
        WHEN listing.department LIKE '%$dep' THEN 1
        WHEN listing.department LIKE '%$dep%' THEN 2
        WHEN listing.department LIKE '%$dep' THEN 3
        WHEN listing.class = '$class' THEN 0
        WHEN listing.class LIKE '%$class' THEN 1
        WHEN listing.class LIKE '%$class%' THEN 2
        WHEN listing.class LIKE '%$class' THEN 3
  ELSE 4
  
  END
EOD;

  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
      echo "<div class='row'>";
      while($row = $result->fetch_assoc()) {

          $moneyFormat = money_format('%(#2n', $row['price']);
          echo generateRow($row['title'], $row['author'], $row['price'], $glyph, $row['listing_id'], 
            $row['department'], $row['class'], $row['timestamp_listed'], $row['first']);

      }
      echo "</div>";
  } else {
      echo "0 results";
  }
  $conn->close();
}

function displayAllListings(){
  require 'php/db_info.php';
  require "settings.php";

  $conn = new mysqli($servername, $username, $password, $dbname);
  $sql = 'SELECT listing.title, listing.listing_id, listing.author, listing.department, listing.class,listing.timestamp_listed,listing.price, user.first FROM listing INNER JOIN user ON listing.seller_id = user.user_id';
  $result = $conn->query($sql);

  $conn->close();

  if ($result->num_rows > 0) {

      echo "<div class='row'>";
      while ($row = $result->fetch_assoc()) {
          
          $sql =<<<SE
            SELECT book_id, buyer_id, amount FROM offer WHERE book_id={$row["listing_id"]} AND buyer_id='{$_SESSION["user_id"]}'
SE;

          $conn = new mysqli($servername, $username, $password, $dbname);

          $glyph = "glyphicon-send";
          if ($conn->query($sql) !== false && $conn->query($sql)->num_rows > 0){
            $glyph = "glyphicon-ok";
          }
          $conn->close();
          
          echo generateRow($row['title'], $row['author'], $row['price'], $glyph, $row['listing_id'],
              $row['department'], $row['class'], $row['timestamp_listed'], $row['first']);
          
      }
  }
}

function generateRow($title, $author, $price, $glyph, $ID, $dep, $class, $time, $name){

  $moneyFormat = money_format('%(#2n', $price);
          setlocale(LC_MONETARY, 'en_US');

  return <<<EOD
          
          <div class="col-md-8 col-md-offset-2 listing clearfix">
            <div class="col-xs-12 mainInfo">
              <div class="col-xs-5">{$title}</div>
              <div class="col-xs-4">{$author}</div>
              <div class="col-xs-2">$ {$moneyFormat}</div>
              <div class="col-xs-1 tab">
                <span class="glyphicon {$glyph}" aria-hidden="true" data-book-id="{$ID}"></span>
              </div>
            </div>
            <div class="col-xs-12 secondaryInfoHeader">
              <div class="col-xs-5">Class</div>
              <div class="col-xs-4">Date Posted</div>
              <div class="col-xs-3">Seller</div>
            </div>
            <div class="col-xs-12 secondaryInfo">
              <div class="col-xs-5">{$dep}-{$class}</div>
              <div class="col-xs-4">{$time}</div>
              <div class="col-xs-3">{$name}</div>
            </div> 
          </div>
EOD;

}

?>

