<?php require "partial/header.php"; ?>
<?php
	loggedin();
	$id = null;
	if (!empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}

	if (null == $id) {
		redirect("index.php");
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM expenses where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		Database::disconnect();
	}
?>

<div class="container">

	<div class="span10 offset1">
		<div class="row">
			<h3>Expense</h3>
		</div>

		<div class="form-horizontal">
			<div class="control-group">
				<label class="control-label">Expense ID</label>
				<div class="controls">
					<label class="checkbox">
						<?php echo $data['id']; ?>
					</label>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Name</label>
				<div class="controls">
					<label class="checkbox">
						<?php echo $data['name']; ?>
					</label>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Amount</label>
				<div class="controls">
					<label class="checkbox">
						<?php echo "$".$data['amount']; ?>
					</label>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Date</label>
				<div class="controls">
					<label class="checkbox">
						<?php echo $data['date']; ?>
					</label>
				</div>
			</div>
			<div class="form-actions">
				<a class="btn" href="index.php">Back</a>
			</div>


		</div>
	</div>

</div>
<?php require "partial/footer.php"; ?>