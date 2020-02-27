<?php require "partial/header.php"; ?>
<?php
	loggedin();
	$id = null;
	if (!empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}

	if ($id == null) {
		redirect("Location: index.php");
	}

	if (!empty($_POST)) {
		// keep track validation errors
		$nameError = null;
		$amountError = null;
		
		// keep track post values
		$name = strip_tags($_POST['name']);
		$amount = strip_tags($_POST['amount']);
		
		// validate input
		$valid = true;
		if (empty($name)) {
			$nameError = 'Please enter Name';
			$valid = false;
		}
		
		if (empty($amount)) {
			$amountError = 'Please enter an amount';
			$valid = false;
		} elseif(!is_numeric($amount)) {
			$amountError = 'Please enter a number';
			$valid = false;
		}

		// update data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE expenses set name = ?, amount = ? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($name, $amount, $id));
			Database::disconnect();
			redirect("index.php");
		}
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM expenses where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$name = $data['name'];
		$amount = $data['amount'];
		Database::disconnect();
	}
?>

<div class="container">

	<div class="span10 offset1">
		<div class="row">
			<h3>Update a Expense</h3>
		</div>

		<form class="form-horizontal" action="u_expense.php?id=<?php echo $id ?>" method="post">
			<div class="control-group <?php echo !empty($nameError) ? 'error' : ''; ?>">
				<label class="control-label">Name</label>
				<div class="controls">
					<input name="name" type="text" placeholder="Name" value="<?php echo !empty($name) ? $name : ''; ?>">
					<?php if (!empty($nameError)) : ?>
						<span class="help-inline"><?php echo $nameError; ?></span>
					<?php endif; ?>
				</div>
			</div>
			<div class="control-group <?php echo !empty($amountError) ? 'error' : ''; ?>">
				<label class="control-label">Amount</label>
				<div class="controls">
					<input name="amount" type="text" placeholder="0000.00" value="<?php echo !empty($amount) ? $amount : ''; ?>">
					<?php if (!empty($amountError)) : ?>
						<span class="help-inline"><?php echo $amountError; ?></span>
					<?php endif; ?>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-success">Update</button>
				<a class="btn" href="index.php">Back</a>
			</div>
		</form>
	</div>

</div>
<?php require "partial/footer.php"; ?>