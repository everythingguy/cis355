<?php require "partial/header.php"; ?>
<?php
	loggedin();
	reportErrors();
	$id = null;

	if (!empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}

	if ($id == null) {
		redirect("index.php");
		exit();
	}

	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM expenses WHERE id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);

	if(!validAccountant($data["user_id"])) {
		redirect("index.php");
		exit();
	}

	if (!empty($_POST)) {
		// delete data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM expenses  WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		Database::disconnect();
		if($_SESSION["index"] == "normal") {
			redirect("index.php");
		} else {
			redirect("index.php?id=".$_SESSION["index"]);
		}
	}
?>

<div class="container">

	<div class="span10 offset1">
		<div class="row">
			<h3>Delete a Expense</h3>
		</div>

		<form class="form-horizontal" action="d_expense.php?id=<?php echo $id; ?>" method="post">
			<input type="hidden" name="id" value="<?php echo $id; ?>" />
			<p class="alert alert-error">Are you sure you want to delete?</p>
			<div class="form-actions">
				<button type="submit" class="btn btn-danger">Yes</button>
				<?php 
					if($_SESSION["index"] == "normal") {
						echo '<a class="btn" href="index.php">No</a>';
					} else {
						echo '<a class="btn" href="index.php?id='.$_SESSION["index"].'">No</a>';
					} 
				?>
			</div>
		</form>
	</div>

</div>
<?php require "partial/footer.php"; ?>