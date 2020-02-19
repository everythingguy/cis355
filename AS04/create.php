<?php 
	#error_reporting(E_ALL); ini_set('display_errors', 1);
	require '../AS03/database/database.php';

	if ( !empty($_POST)) {
		// keep track validation errors
		$eventError = null;
		$customerError = null;
		
		// keep track post values
		$event = strip_tags($_POST['event']);
		$customer = strip_tags($_POST['customer']);
		
		// validate input
		$valid = true;
		if (empty($event) || $event == "error") {
			$eventError = 'Please select a event';
			$valid = false;
		}
		
		if (empty($customer) || $event == "error") {
			$customerError = 'Please select a person';
			$valid = false;
		}
		
		// insert data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO assignments (event_id,customer_id) values(?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($event,$customer));
			Database::disconnect();
			header("Location: assignments.php");
		}
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="../AS03/css/bootstrap.min.css" rel="stylesheet">
    <script src="../AS03/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
    
    			<div class="span10 offset1">
    				<div class="row">
		    			<h3>Create a Assignment</h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="create.php" method="post">
					  <div class="control-group <?php echo !empty($eventError)?'error':'';?>">
					    <label class="control-label">Event</label>
					    <div class="controls">
					      	<select name="event" type="text" placeholder="event" value="<?php echo !empty($event)?$event:'';?>">
								<option value="error">Select a Event</option>
								<?php 
									require_once '../AS03/database/database.php';
									$pdo = Database::connect();
									$sql = 'SELECT * FROM events';
									foreach($pdo->query($sql) as $table) { 
										echo "<option value='". $table["id"] . "'>" . $table["description"] . "</option>";
									}
								?>
							</select>
					      	<?php if (!empty($eventError)): ?>
					      		<span class="help-inline"><?php echo $eventError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($customerError)?'error':'';?>">
					    <label class="control-label">Person</label>
					    <div class="controls">
					      	<select name="customer" type="text"  placeholder="person" value="<?php echo !empty($customer)?$customer:'';?>">
							  <option value="error">Select a Person</option>
							  <?php 
									require_once '../AS03/database/database.php';
									$pdo = Database::connect();
									$sql = 'SELECT * FROM customers';
									foreach($pdo->query($sql) as $table) { 
										echo "<option value='". $table["id"] . "'>" . $table["name"] . "</option>";
									}
								?>
							</select>
					      	<?php if (!empty($customerError)): ?>
					      		<span class="help-inline"><?php echo $customerError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Create</button>
						  <a class="btn" href="assignments.php">Back</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>