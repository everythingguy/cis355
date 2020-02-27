<?php require_once "partial/header.php"; ?>

<?php
    if (!empty($_POST)) {
        $error = null;

        $username = $_POST["username"];
        $password = $_POST["password"];

        if (empty($username) || empty($password)) {
            $error = "Please enter a username and password";
        } else {
            $passwordhash = md5($password);

            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM users WHERE username = ? AND password = ? LIMIT 1";
            $q = $pdo->prepare($sql);
            $q->execute(array($username, $passwordhash));
            $data = $q->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $_SESSION["loggedin"] = true;
                $_SESSION["user_ID"] = $data["id"];
                $_SESSION["username"] = $data["username"];
                $_SESSION["income"] = $data["income"];
                if (!empty($data["picture"])) $_SESSION["picture"] = base64_encode($data["picture"]);
                header("Location: index.php");
            } else {
                $error = "The username or password doesn't match our records";
            }

            Database::disconnect();
        }
    }
?>

<div class="container">
    <div class="row">
        <div>
            <?php if (!empty($error)) : ?>
                <div class="control-group error">
                    <div class="controls">
                        <span class="help-inline">
                            <ul><?php echo $error; ?></ul>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
            <form class="form-horizontal" method="post" action="login.php">
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
                <div class="form-actions">
                    <a class="btn" href="create.php">Create An Account</a>
                    <button type="submit" class="btn btn-success">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once "partial/footer.php"; ?>