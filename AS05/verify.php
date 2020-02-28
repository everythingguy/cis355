<?php require_once "partial/header.php"; ?>

<?php
    $status = "<h1>Error verifing your account</h1>";

    if(!empty($_GET["verifyHash"])) {
        $pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM users where verifyHash = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($_GET["verifyHash"]));
		$data = $q->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        
        if($data) {
            $status = "<h1>Successfully verified your account</h1>";
        
            $pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE users set verified = 1 WHERE verifyHash = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($_GET["verifyHash"]));
            Database::disconnect();
            
            echo "<script>setTimeout(() => {location.href='login.php'}, 3000);</script>";
        }
    }
?>

<div class="container">

	<div class="row" style="text-align: center;">
        <?php echo $status; ?>
    </div>

</div>

<?php require_once "partial/footer.php"; ?>