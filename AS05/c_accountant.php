<?php require "partial/header.php"; ?>
<?php
    loggedin();

    if (!empty($_POST)) {
        // keep track validation errors
        $accountantError = null;

        // keep track post values
        $accountant = strip_tags($_POST['accountant']);

        // validate input
        $valid = true;
        if (empty($accountant) || $accountant == "error") {
            $accountantError = 'Please select a accountant';
            $valid = false;
        }

        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO user_accountant (user_id, accountant_id) values(?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($_SESSION["user_ID"], $accountant));
            Database::disconnect();
            redirect("accountants.php");
        }
    }
?>
<div class="container">

    <div class="span10 offset1">
        <div class="row">
            <h3>Add Accountant</h3>
        </div>

        <form class="form-horizontal" action="c_accountant.php" method="post">
            <div class="control-group <?php echo !empty($accountantError) ? 'error' : ''; ?>">
                <label class="control-label">Accountant</label>
                <div class="controls">
                    <select name="accountant" type="text" placeholder="accountant" value="<?php echo !empty($accountant) ? $accountant : ''; ?>">
                        <option value="error">Select a Accountant</option>
                        <?php
                            $pdo = Database::connect();
                            $sql = 'SELECT *, u.id AS userid FROM users AS u LEFT JOIN user_accountant AS u_a ON u.id=u_a.accountant_id WHERE u.id !='.$_SESSION["user_ID"];
                            foreach ($pdo->query($sql) as $table) {
                                if($_SESSION["user_ID"] != $table["user_id"]) echo "<option value='" . $table["userid"] . "'>" . $table["username"] . "</option>";
                            }
                        ?>
                    </select>
                    <?php if (!empty($accountantError)) : ?>
                        <span class="help-inline"><?php echo $accountantError; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-success">Create</button>
                <a class="btn" href="accountants.php">Back</a>
            </div>
        </form>
    </div>

</div>
<?php require "partial/footer.php"; ?>