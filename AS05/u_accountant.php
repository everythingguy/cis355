<?php require "partial/header.php"; ?>
<?php
	loggedin();
	$id = null;
	if (!empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}

	if ($id == null) {
		redirect("accountants.php");
	}

	if (!empty($_POST)) {
		// keep track validation errors
		$priceError = null;
		
		// keep track post values
		$price = strip_tags($_POST['price']);
		
		// validate input
		$valid = true;
        if(empty($price)) {
            $priceError = 'Please enter a price';
            $valid = false;
        }

        if(!is_numeric($price)) {
            $priceError = 'Please enter a numeric price';
            $valid = false;
        }

		// update data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE user_accountant set price = ?, accepted = 'No' WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($price, $id));
			Database::disconnect();
			redirect("accountants.php");
		}
	} else {
        $pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT *, u_a.id AS uaid FROM user_accountant AS u_a INNER JOIN users AS u ON u_a.accountant_id=u.id where u_a.id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$username = $data['username'];
        $price = $data['price'];
		Database::disconnect();
	}
?>

<div class="container">

	<div class="span10 offset1">
		<div class="row">
			<h3>Update a Accountant</h3>
		</div>

		<form class="form-horizontal" action="u_accountant.php?id=<?php echo $id ?>" method="post">
            <div class="control-group">
				<label class="control-label">Accountant</label>
				<div class="controls">
					<label class="checkbox">
						<?php echo $data['username']; ?>
					</label>
				</div>
			</div>
			<div class="control-group <?php echo !empty($priceError) ? 'error' : ''; ?>">
				<label class="control-label">Price</label>
				<div class="controls">
					<input name="price" type="text" placeholder="0000.00" value="<?php echo !empty($price) ? $price : ''; ?>">
					<?php if (!empty($priceError)) : ?>
						<span class="help-inline"><?php echo $priceError; ?></span>
					<?php endif; ?>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-success">Update</button>
				<a class="btn" href="accountants.php">Back</a>
			</div>
		</form>
	</div>

</div>
<?php require "partial/footer.php"; ?>