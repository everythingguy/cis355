<?php require "partial/header.php"; ?>

<?php
    loggedin();
    $_SESSION["index"] = "normal";
    if(!empty($_GET['id'])) {
        $_SESSION["index"] = $_GET['id'];
    }
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
                    $sql = "SELECT *, id AS eid FROM expenses WHERE Month(date) = $month AND user_id = ".$_SESSION["user_ID"]." ORDER BY id DESC";
                    if(!empty($_GET['id'])) {
                        $sql = "SELECT *, e.id AS eid FROM expenses AS e INNER JOIN user_accountant AS u_a ON u_a.user_id=e.user_id INNER JOIN users AS u ON e.user_id=u.id WHERE u_a.accountant_id=".$_SESSION["user_ID"]." AND e.user_id=".$_GET['id'];
                    }
                    $data = $pdo->query($sql);
                    $once = true;
                    $income = $_SESSION['income'];
                    foreach ($data as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td>' . $row['amount'] . '</td>';
                        echo '<td width=250>';
                        echo '<a class="btn" href="r_expense.php?id=' . $row['eid'] . '">Read</a>';
                        echo '&nbsp;';
                        echo '<a class="btn btn-success" href="u_expense.php?id=' . $row['eid'] . '">Update</a>';
                        echo '&nbsp;';
                        echo '<a class="btn btn-danger" href="d_expense.php?id=' . $row['eid'] . '">Delete</a>';
                        echo '</td>';
                        echo '</tr>';

                        $totalExpense += (double)$row['amount'];

                        if(!empty($_GET['id']) && $once) {
                            $income = $row['income'];
                            $once = false;
                        }
                    }
                    
                    Database::disconnect();
                ?>
            </tbody>
        </table>
        <?php
            $neg = "";
            $flow = $income - $totalExpense;
            if($income < $totalExpense) {
                $neg = "-";
                $flow = $flow * -1;
            }
            echo "<p style='display: inline-block; width: 33%;'>Monthly Income: $".$income."</p>";
            echo "<p style='display: inline-block; text-align: center; width: 33%;'>Total Spent: ".$totalExpense."</p>";
            echo "<p style='display: inline-block; text-align: right; width: 33%;'>Cash Flow: $neg$$flow</p>";
        ?>
    </div>
</div>

<?php require "partial/footer.php"; ?>