<?php require "settings.php"; ?>

<link href="css/header.css" rel="stylesheet">

<div id="nav" class="row">
    <div class="col-md-5">
      <h1><a href=<?php echo "index.php" ?> >Tufts Textbook Exchange</a></h1>
    </div>
    <div class="col-md-1 col-md-offset-5">
      <img id="profileImage" src="<?php echo $_SESSION['user_img']; ?>" class="img-circle">
      <div class="col-md-12">
        <div id="dropdown">
          <h4><?php echo $_SESSION['user_name']; ?></h4>
          <a href="profile.php">Go to profile</a>
          <a href="logout.php">Logout</a>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript" src="js/header.js"></script>

<?php 
  if(isset($_GET['action']) && $_GET['action'] === 'logout'){
      $facebook->destroySession();
  }
?>
