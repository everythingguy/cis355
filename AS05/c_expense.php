<?php require "partial/header.php"; ?>
<?php
	loggedin();

	if($_SESSION["index"] != "normal") {
		if(!validAccountant($_SESSION["index"])) {
			redirect("index.php");
			exit();
		}
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
		
		// insert data
		if ($valid) {
			$date = date('Y-m-d H:i:s');
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO expenses (name,amount,user_id,date) values(?, ?, ?,?)";
			$q = $pdo->prepare($sql);
			if($_SESSION['index'] == "normal") $q->execute(array($name,$amount,$_SESSION["user_ID"],$date));
			else $q->execute(array($name,$amount,$_SESSION["index"],$date));
			Database::disconnect();
			if($_SESSION["index"] == "normal") {
				redirect("index.php");
			} else {
				redirect("index.php?id=".$_SESSION["index"]);
			}
		}
	}
?>
<div class="container">

	<div class="span10 offset1">
		<div class="row">
			<h3>Log an Expense</h3>
		</div>

		<form class="form-horizontal" action="c_expense.php" method="post">
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
				<button type="submit" class="btn btn-success">Log</button>
				<?php 
					if($_SESSION["index"] == "normal") {
						echo '<a class="btn" href="index.php">Back</a>';
					} else {
						echo '<a class="btn" href="index.php?id='.$_SESSION["index"].'">Back</a>';
					} 
				?>
			</div>
		</form>
	</div>
</div>
<?php require "partial/footer.php"; ?>