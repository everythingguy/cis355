<?php require "partial/header.php"; ?>
<?php
    loggedin();

	if (!empty($_POST)) {
		// keep track validation errors
		$priceError = null;
		
		// keep track post values
        $price = strip_tags($_POST['price']);
        $id = $_GET['id'];
		
		// validate input
        $valid = true;
        if(empty($_GET['id'])) {
            $valid = false;
            redirect("accept_accountant.php");
        }

		if (empty($price)) {
			$priceError = 'Please enter a price';
			$valid = false;
		} elseif(!is_numeric($price)) {
            $priceError = 'Please enter a numeric value';
            $valid = false;
        }
		
		// insert data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE user_accountant set price = ?, accepted = 'Pending Price Negotiation' WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($price, $id));
			Database::disconnect();
			redirect("accept_accountant.php");
		}
	}
?>
<div class="container">

	<div class="span10 offset1">
		<div class="row">
			<h3>Negotiate a Price</h3>
		</div>

		<form class="form-horizontal" action="accountant_price.php?id=<?php echo $_GET["id"]; ?>" method="post">
			<div class="control-group <?php echo !empty($priceError) ? 'error' : ''; ?>">
				<label class="control-label">Price</label>
				<div class="controls">
					<input name="price" type="text" placeholder="Price" value="<?php echo !empty($price) ? $price : ''; ?>">
					<?php if (!empty($priceError)) : ?>
						<span class="help-inline"><?php echo $priceError; ?></span>
					<?php endif; ?>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-success">Submit</button>
				<a class="btn" href="accept_accountant.php">Back</a>
			</div>
		</form>
	</div>
</div>
<?php require "partial/footer.php"; ?>