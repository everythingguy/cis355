<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="../AS03/css/bootstrap.min.css" rel="stylesheet">
    <script src="../AS03/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
    		<div class="row">
    			<h3>PHP CRUD Grid</h3>
    		</div>
			<div class="row">
				<p style="display: inline-block;">
					<a href="../AS03/as03.php" class="btn btn-danger">Back</a>
				</p>
				<p style="display: inline-block;">
					<a href="create.php" class="btn btn-success">Create</a>
				</p>
				
				<?php
                    require_once '../AS03/database/database.php';
                    $pdo = Database::connect();
                    $sql = 'SELECT * FROM events';
                    foreach($pdo->query($sql) as $table) {
                            echo "<h1>". $table['description'] . "</h1>";
                            echo "<h4 style='display:inline-block;'>When: " . $table['date'] . " " . $table["time"] . "</h4><h4 style='display: inline-block; float: right;''>Location: " . $table['location'] . "</h4>";
                        ?>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                    <th>Name</th>
                                    <th>Email Address</th>
                                    <th>Mobile Number</th>
                                    <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                        <?php
                             require_once '../AS03/database/database.php';
                             $pdo = Database::connect();
                             $sql = 'SELECT * FROM customers AS c INNER JOIN assignments AS a ON c.id=a.customer_id WHERE event_id=' . $table['id'] . ' ORDER BY c.id DESC';
                              foreach ($pdo->query($sql) as $row) {
                                         echo '<tr>';
                                         echo '<td>'. $row['name'] . '</td>';
                                         echo '<td>'. $row['email'] . '</td>';
                                         echo '<td>'. $row['mobile'] . '</td>';
                                         echo '<td width=250>';
                                         echo '<a class="btn" href="read.php?id='.$row['id'].'">Read</a>';
                                         echo '&nbsp;';
                                         echo '<a class="btn btn-success" href="update.php?id='.$row['id'].'">Update</a>';
                                         echo '&nbsp;';
                                         echo '<a class="btn btn-danger" href="delete.php?id='.$row['id'].'">Delete</a>';
                                         echo '</td>';
                                         echo '</tr>';
                             }
                             Database::disconnect();
                        ?>
                                </tbody>
                            </table>
                        <?php   
                    }
                ?>
    	</div>
    </div> <!-- /container -->
  </body>
</html>