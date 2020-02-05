<?php
# connect
include("database.php");

$n = $_POST['msg'];
$id = $_GET['id'];
$sql = "UPDATE messages SET message = '$n' WHERE id = $id";
$pdo->query($sql);
echo "<p>Your info has been updated</p><br>";
echo "<a href='display_list.php'>Back to list</a>";
?>