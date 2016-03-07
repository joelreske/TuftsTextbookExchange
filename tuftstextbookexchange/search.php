<?php session_start(); ?>

<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tufts Textbook Exchange | Search</title>
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
        </div>
      </div>

      <span id="recentListings">
        <div class="row">
          <div class="col-md-8 col-md-offset-2">
            <h4>Search Results:</h4>
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

    if(isset($_POST['searchBooks'])){
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $searchString = $_POST['title'];
        $sql = <<<EOD
        SELECT books.title, books.author, books.department, books.class,books.timePosted, books.price, users.first FROM books INNER JOIN users ON books.sellerID = users.ID
        WHERE books.title LIKE '%$searchString%'
        ORDER BY CASE WHEN books.title = '$searchString' THEN 0
                    WHEN books.title LIKE '$searchString%' THEN 1
                    WHEN books.title LIKE '%$searchString%' THEN 2
                    WHEN books.title LIKE '%$searchString' THEN 3
        ELSE 4
        END, books.title ASC
EOD;

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<div class='row'>";
            while($row = $result->fetch_assoc()) {
                $moneyFormat = money_format('%(#2n', $row['price']);
                echo <<<EOT
                    <div class="col-md-8 col-md-offset-2 listing">
                      <div class="col-xs-12 mainInfo">
                        <div class="col-xs-5">{$row['title']}</div>
                        <div class="col-xs-4">{$row['author']}</div>
                        <div class="col-xs-2">$ {$moneyFormat}</div>
                        <div id="tab" class="col-xs-1">
                          <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                        </div>
                      </div>
                      <div class="col-xs-12 secondaryInfoHeader">
                        <div class="col-xs-5">Class</div>
                        <div class="col-xs-4">Date Posted</div>
                        <div class="col-xs-3">Seller</div>
                      </div>
                      <div class="col-xs-12 secondaryInfo">
                        <div class="col-xs-5">{$row['department']}-{$row['class']}</div>
                        <div class="col-xs-4">{$row['timePosted']}</div>
                        <div class="col-xs-3">{$row['first']}</div>
                      </div> 
                    </div>
EOT;
            }
            echo "</div>";
        } else {
            echo "0 results";
        }
        $conn->close();
    }
?>
      </span>

    </div>
    

  </body>
  
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>


</html>

