<?php require_once "partial/header.php"; ?>

<?php
    require_once "database/database.php";

    if (!empty($_POST)) {
        $error = null;

        $email = strip_tags($_POST["email"]);
        $username = strip_tags($_POST["username"]);
        $password = strip_tags($_POST["password"]);
        $password2 = strip_tags($_POST["password2"]);
        $phone = strip_tags($_POST["phone"]);
        $address = strip_tags($_POST["address"]);
        $income = strip_tags($_POST["income"]);

        if (empty($username) || empty($password) || empty($address) || empty($income)) {
            $error = $error . "<li>Please finish the form</li>";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = $error . "<li>Please enter a valid email</li>";
        }
        if ($password != $password2) {
            $error = $error . "<li>The passwords do not match</li>";
        }
        if (!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone)) {
            $error = $error . "<li>Enter a phone number in the formate 000-000-0000</li>";
        }
        if (!is_numeric($income)) {
            $error = $error . "<li>Please enter a numeric income</li>";
        }

        if (empty($error)) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
            $q = $pdo->prepare($sql);
            $q->execute(array($username));
            $data = $q->fetch(PDO::FETCH_ASSOC);
            Database::disconnect();

            if ($data) {
                $error = $error . "<li>There is already an account with that username</li>";
            } else {
                $passwordhash = md5($password);

                $pdo = Database::connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "INSERT INTO users (email, username, password, phone, address, income) value(?,?,?,?,?,?)";
                $q = $pdo->prepare($sql);
                $q->execute(array($email, $username, $passwordhash, $phone, $address, $income));
                Database::disconnect();
                header("Location: login.php");
            }
        }
    }
?>

<div class="container">
    <div class="row">
        <div>
            <form class="form-horizontal" method="post" action="create.php">
                <?php if (!empty($error)) : ?>
                    <div class="control-group error">
                        <div class="controls">
                            <span class="help-inline">
                                <ul><?php echo $error; ?></ul>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="control-group">
                    <label class="control-label">Email</label>
                    <div class="controls">
                        <input name="email" type="text" placeholder="Email" value="<?php echo !empty($email) ? $email : ''; ?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Username</label>
                    <div class="controls">
                        <input name="username" type="text" placeholder="Username" value="<?php echo !empty($username) ? $username : ''; ?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Password</label>
                    <div class="controls">
                        <input name="password" type="password" placeholder="Password">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Reenter Password</label>
                    <div class="controls">
                        <input name="password2" type="password" placeholder="Password">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Phone Number</label>
                    <div class="controls">
                        <input name="phone" type="text" placeholder="Phone Number" value="<?php echo !empty($phone) ? $phone : ''; ?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Address</label>
                    <div class="controls">
                        <input name="address" type="text" placeholder="Address" value="<?php echo !empty($address) ? $address : ''; ?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Monthly Income</label>
                    <div class="controls">
                        <input name="income" type="text" placeholder="Income" value="<?php echo !empty($income) ? $income : ''; ?>">
                    </div>
                </div>
                <div class="form-actions">
                    <a class="btn" href="login.php">Login Page</a>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once "partial/footer.php"; ?>