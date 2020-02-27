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
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <?php if(isset($_SESSION["user_ID"])): ?>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php if(!empty($_SESSION["picture"])) echo "<img style='margin-right: 10px;' height='25px' width='25px' src='data:image/;base64,".$_SESSION['picture']."'/>"; echo $_SESSION["username"]; ?></a>
          <ul class="dropdown-menu">
            <li><a href="upload.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </li>
      <?php endif;?>
    </ul>
  </div>
</nav>
