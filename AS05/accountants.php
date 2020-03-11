<?php require "partial/header.php"; ?>

<?php
    loggedin();

    if(!empty($_GET['id'])) {
        $pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "UPDATE user_accountant set accepted = 'Yes' WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($_GET['id']));
		Database::disconnect();
		redirect("accountants.php");
    }
?>

<div class="container">
    <div class="row">
        <h3 style="display: inline-block;">Accountants</h3>
        <p style="float: right;">
            <a href="c_accountant.php" class="btn btn-success">Create</a>
        </p>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Accepted</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $totalExpense = 0;
                    $pdo = Database::connect();
                    $month = date("m");
                    $sql = "SELECT *, u_a.id AS aid FROM user_accountant AS u_a INNER JOIN users AS u ON u.id=u_a.accountant_id WHERE u_a.user_id= ".$_SESSION["user_ID"]." ORDER BY u_a.id DESC";
                    foreach ($pdo->query($sql) as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['username'] . '</td>';
                        echo '<td>' . $row['price'] . '</td>';
                        echo '<td>' . $row['accepted'] . '</td>';
                        echo '<td width=250>';
                        if($row['accepted'] == "Pending Price Negotiation") {
                            echo '<a class="btn btn-success" href="accountants.php?id=' . $row['aid'] . '">Accept</a>';
                            echo '<a class="btn btn-danger" href="u_accountant.php?id=' . $row['aid'] . '">Change Price</a><br><br>';
                        }
                        echo '<a class="btn" href="r_accountant.php?id=' . $row['aid'] . '">Read</a>';
                        echo '&nbsp;';
                        echo '<a class="btn btn-success" href="u_accountant.php?id=' . $row['aid'] . '">Update</a>';
                        echo '&nbsp;';
                        echo '<a class="btn btn-danger" href="d_accountant.php?id=' . $row['aid'] . '">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                        $totalExpense += (double)$row['price'];
                    }
                    Database::disconnect();
                ?>
            </tbody>
        </table>
        <?php
            echo "<p style='display: inline-block; width: 33%;'>Total Price: $".$totalExpense."</p>";
        ?>
    </div>
</div>

<?php require "partial/footer.php"; ?>