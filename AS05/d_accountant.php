<?php require "partial/header.php"; ?>
<?php
	loggedin();
	$id = 0;

	if (!empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}

	if (!empty($_POST)) {
		// keep track post values
		$id = $_POST['id'];

		// delete data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM user_accountant  WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		Database::disconnect();
		redirect("accountants.php");
	}
?>

<div class="container">

	<div class="span10 offset1">
		<div class="row">
			<h3>Delete a Accountant</h3>
		</div>

		<form class="form-horizontal" action="d_accountant.php" method="post">
			<input type="hidden" name="id" value="<?php echo $id; ?>" />
			<p class="alert alert-error">Are you sure you want to delete?</p>
			<div class="form-actions">
				<button type="submit" class="btn btn-danger">Yes</button>
				<a class="btn" href="accountants.php">No</a>
			</div>
		</form>
	</div>

</div>
<?php require "partial/footer.php"; ?>