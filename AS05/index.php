<?php require "partial/header.php"; ?>

<?php
    loggedin();
?>

<div class="container">
    <div class="row">
        <h3 style="display: inline-block;">Expenses - 1 Month</h3>
        <p style="float: right;">
            <a href="c_expense.php" class="btn btn-success">Create</a>
        </p>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $totalExpense = 0;
                    $pdo = Database::connect();
                    $month = date("m");
                    $sql = "SELECT * FROM expenses WHERE Month(date) = $month AND user_id = ".$_SESSION["user_ID"]." ORDER BY id DESC";
                    foreach ($pdo->query($sql) as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td>' . $row['amount'] . '</td>';
                        echo '<td width=250>';
                        echo '<a class="btn" href="r_expense.php?id=' . $row['id'] . '">Read</a>';
                        echo '&nbsp;';
                        echo '<a class="btn btn-success" href="u_expense.php?id=' . $row['id'] . '">Update</a>';
                        echo '&nbsp;';
                        echo '<a class="btn btn-danger" href="d_expense.php?id=' . $row['id'] . '">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                        $totalExpense += (double)$row['amount'];
                    }
                    Database::disconnect();
                ?>
            </tbody>
        </table>
        <?php
            $neg = "";
            $flow = $_SESSION['income'] - $totalExpense;
            if($_SESSION['income'] < $totalExpense) {
                $neg = "-";
                $flow = $flow * -1;
            }
            echo "<p style='display: inline-block; width: 33%;'>Monthly Income: $".$_SESSION['income']."</p>";
            echo "<p style='display: inline-block; text-align: center; width: 33%;'>Total Spent: ".$totalExpense."</p>";
            echo "<p style='display: inline-block; text-align: right; width: 33%;'>Cash Flow: $neg$$flow</p>";
        ?>
    </div>
</div>

<?php require "partial/footer.php"; ?>