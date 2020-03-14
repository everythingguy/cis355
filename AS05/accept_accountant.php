<?php require "partial/header.php"; ?>

<?php
    loggedin();

    if(!empty($_GET['mode']) && !empty($_GET['id'])) {
        $mode = $_GET['mode'];

        if($mode == "Accept") {
            $pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE user_accountant set accepted = 'Yes' WHERE id = ? AND accountant_id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($_GET['id'], $_SESSION['user_ID']));
			Database::disconnect();
			redirect("accept_accountant.php");
        } elseif($mode == "Decline") {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DELETE FROM user_accountant WHERE id = ? AND accountant_id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($_GET['id'], $_SESSION['user_ID']));
            redirect("accept_accountant.php");
            Database::disconnect();
        }
    }
?>

<div class="container">
    <div class="row">
        <h3 style="display: inline-block;">Clients</h3>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Picture</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Accepted</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $pdo = Database::connect();
                    $sql = "SELECT *, u_a.id AS aid FROM user_accountant AS u_a INNER JOIN users AS u ON u.id=u_a.user_id WHERE u_a.accountant_id=".$_SESSION["user_ID"]." ORDER BY u_a.id DESC";
                    foreach ($pdo->query($sql) as $row) {
                        echo '<tr>';
                        if($row['picture']) echo "<td><img style='display: block; margin: 0 auto;' height='100px' width='100px' src='data:image/;base64,".base64_encode($row['picture'])."'/></td>";
                        else echo "<td></td>";
                        echo '<td>' . $row['username'] . '</td>';
                        echo '<td>' . $row['price'] . '</td>';
                        echo '<td>' . $row['accepted'] . '</td>';
                        echo '<td width=250>';
                        if($row['accepted'] == "No") echo '<a class="btn btn-success" href="accept_accountant.php?mode=Accept&id=' . $row['aid'] . '">Accept</a>';
                        echo '&nbsp;';
                        if($row['accepted'] == "No") echo '<a class="btn" href="accountant_price.php?id=' . $row['aid'] . '">Price</a>';
                        echo '&nbsp;';
                        $remove = "Decline";
                        if($row['accepted'] == "Yes") {
                            $remove = "Remove";
                            echo '<a class="btn btn-success" href="index.php?id=' . $row['user_id'] . '">View</a>';
                        }
                        echo '<a class="btn btn-danger" href="accept_accountant.php?mode=Decline&id=' . $row['aid'] . '">'.$remove.'</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    Database::disconnect();
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require "partial/footer.php"; ?>