<?php
# connect
include("database.php");

$id = $_GET['id'];
$sql = "DELETE FROM messages WHERE id = $id";
$pdo->query($sql);
echo "<p>Your info has been deleted</p><br>";
echo "<a href='display_list.php'>Back to list</a>";
?>