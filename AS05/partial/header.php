<!DOCTYPE html>
<html style="height: 100%; width: 100%;">
<head>
  <title>Duraken</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../AS03/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<style>
  .container {
    max-width: 100%;
  }
</style>

<?php
    session_start();
    require_once "database/database.php";

    function reportErrors() {
      error_reporting(E_ALL); 
      ini_set('display_errors', 1);
    }

    function loggedin() {
      if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
        redirect("login.php");
      }
    }

    function redirect($url) {
      echo "<script> location.href='$url'; </script>";
    }

    function validAccountant($user_id) {
      if($user_id != $_SESSION['user_ID']) {
        $validAccountant = false;
        
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM user_accountant WHERE user_id=? AND accountant_id=?';
        $q = $pdo->prepare($sql);
        $q->execute(array($user_id, $_SESSION['user_ID']));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        if($data) $validAccountant = true;
    
        return $validAccountant;
      } else return true;
    }
?>

<body style="height: 100%; width: 100%;">

<nav class="navbar navbar-default" style="width: 100%;">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">Duraken</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="index.php?months=3">3 Months</a></li>
      <li><a href="index.php?months=6">6 Months</a></li>
      <li><a href="index.php?months=12">12 Months</a></li>
      <?php if(isset($_SESSION["user_ID"]) && $_SESSION["user_ID"] == 1) { 
        echo "<li><a href='http://10.0.0.194:9393' target='_blank'>phpmyadmin</a></li>"; 
      } ?>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <?php if(isset($_SESSION["user_ID"])): ?>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php if(!empty($_SESSION["picture"])) echo "<img style='margin-right: 10px;' height='25px' width='25px' src='data:image/;base64,".$_SESSION['picture']."'/>"; echo $_SESSION["username"]; ?></a>
          <ul class="dropdown-menu">
            <li><a href="upload.php">Settings</a></li>
            <?php
              if(isset($_SESSION["user_ID"])) {
                $pdo = Database::connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "SELECT * FROM user_accountant AS u_a WHERE accountant_id=".$_SESSION["user_ID"]." AND accepted='No'";
                $q = $pdo->prepare($sql);
                $q->execute();
                $data = $q->fetch(PDO::FETCH_ASSOC);
                Database::disconnect();

                if($data) echo "<li><a href='accept_accountant.php'>Pending Accountant Rquests</a></li>";
                else {
                    $pdo = Database::connect();
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "SELECT * FROM user_accountant AS u_a WHERE accountant_id=".$_SESSION["user_ID"];
                    $q = $pdo->prepare($sql);
                    $q->execute();
                    $data = $q->fetch(PDO::FETCH_ASSOC);
                    Database::disconnect();

                    if($data) echo "<li><a href='accept_accountant.php'>Manage Clients</a></li>";
                }

                $pdo = Database::connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "SELECT * FROM user_accountant AS u_a WHERE user_id=".$_SESSION["user_ID"]." AND accepted='Pending Price Negotiation'";
                $q = $pdo->prepare($sql);
                $q->execute();
                $data = $q->fetch(PDO::FETCH_ASSOC);
                Database::disconnect();

                if($data) echo "<li><a href='accountants.php'>Pending Price Negotiation</a></li>";
                else echo "<li><a href='accountants.php'>Manage Accountants</a></li>";
              }
            ?>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </li>
      <?php endif;?>
    </ul>
  </div>
</nav>
