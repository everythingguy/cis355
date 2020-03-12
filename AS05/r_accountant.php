<?php require "partial/header.php"; ?>
<?php
    loggedin();
	$id = null;
	if (!empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}

	if (null == $id) {
		redirect("accountants.php");
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM user_accountant AS u_a INNER JOIN users AS u ON u_a.accountant_id=u.id WHERE u_a.id = ? AND u_a.user_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id, $_SESSION['user_ID']));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		Database::disconnect();
	}
?>

<div class="container">

	<div class="span10 offset1">
		<div class="row">
			<h3>Accountant</h3>
		</div>

		<div class="form-horizontal">
			<div class="control-group">
				<label class="control-label">Accountant</label>
				<div class="controls">
					<label class="checkbox">
						<?php echo $data['username']; ?>
					</label>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Price</label>
				<div class="controls">
					<label class="checkbox">
						<?php echo $data['price']; ?>
					</label>
				</div>
			</div>
			<div class="form-actions">
				<a class="btn" href="accountants.php">Back</a>
			</div>


		</div>
	</div>

</div>
<?php require "partial/footer.php"; ?>