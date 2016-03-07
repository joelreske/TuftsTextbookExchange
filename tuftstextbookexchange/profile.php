<?php session_start(); ?>

<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tufts Textbook Exchange | Profile</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <script src="js/jquery-2.1.4.min.js"></script>

  </head>
  <body>

    <div class="container-fluid">

      <?php include 'php/header.php'; ?>

      <div class="row" id="profileInfoContainer">
        <div class="col-md-2 col-md-offset-2">
          <div style="padding:40px;">
            <img class="img-circle" src="<?php echo $_SESSION['user_img']; ?>" width=200 height=200>
          </div>
        </div>
        <div  class="col-md-5" id="profileText">
          <div class="col-md-12">
            <h2><?php echo $_SESSION['user_name']; ?></h2>
          </div>
          <div class="col-md-12">
            <h5><?php echo $_SESSION['user_email']; ?></h5>
          </div>
          <div class="col-md-12">
            <h5><?php echo $_SESSION['user_phone']; ?></h5>
          </div>
          <div class="col-md-12">
            <h5>xx Sold - xx Bought</h5>
          </div>
          <div class="col-md-12">
            <h5>Member Since: <?php echo $_SESSION['timestamp_joined']; ?></h5>
          </div>
        </div>
      </div>
      <div class="row">


      <div class="row" name="sell">
        <div class="col-md-8 col-md-offset-2">
          <h4>Want to sell your used books?</h4>
        </div>
      </div>
      <div class="row">
        <form id="submitNewBook" action="" method="post" class="col-md-8 col-md-offset-2 form">

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

          <div class="col-md-5 col-md-offset-1">
            <div class="col-md-4">Price:</div>
            <div class="col-md-8"><input type="number" name="price"  placeholder="40"></div>
          </div>

          <div class="col-md-4 col-md-offset-4">
            <input class="button" type="submit" name="addBook" value="addBook">
          </div>
        </form>
      </div>

    </div>

<?php
        include 'php/db_info.php';

        if ($_POST['addBook'] == 'addBook') {
            $conn = new mysqli($servername, $username, $password, $dbname);

            $title = $_POST['title'];
            $author = $_POST['author'];
            $department = $_POST['department'];
            $class = $_POST['class'];
            $price = $_POST['price'];
            $Id = $_SESSION['user_id'];

            $sql = <<<EOD
        INSERT INTO listing (title, seller_id, author, price, department, class)
        VALUES ('$title', '$Id', '$author', $price, '$department', '$class')
EOD;

            $result = $conn->query($sql);

            if ($result === true) {
                echo 'New record created successfully';
            } else {
                echo 'Error: '.$sql.'<br>'.$conn->error;
            }

            $conn->close();
        }
?>

  </body>

  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>


</html>
