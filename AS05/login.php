<?php require_once "partial/header.php"; ?>

<?php
    require_once "api.php";
    $error = null;

    if(isLoggedIn()) {
        redirect("index.php");
    }

    if(!empty($_GET) && $_GET["new"]) {
        $error = $error . "<li>Please verify your email before logging in!</li>";
    }

    if (!empty($_POST)) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if (empty($username) || empty($password)) {
            $error = $error . "<li>Please enter a username and password</li>";
        } else {
            //try each pepper
            for($i = 97; $i < 123; $i++) {
                $pepper = chr($i);
                $passwordhash = md5($salt.$password.$pepper);

                $pdo = Database::connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "SELECT * FROM users WHERE username = ? AND password = ? LIMIT 1";
                $q = $pdo->prepare($sql);
                $q->execute(array($username, $passwordhash));
                $data = $q->fetch(PDO::FETCH_ASSOC);

                if ($data && $data["verified"]) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["user_ID"] = $data["id"];
                    $_SESSION["username"] = $data["username"];
                    $_SESSION["income"] = $data["income"];
                    if (!empty($data["picture"])) $_SESSION["picture"] = base64_encode($data["picture"]);
                    header("Location: index.php");
                    break;
                }

                Database::disconnect();
            }

            $error = $error . "<li>The username or password doesn't match our records. Make sure your email is verified.</li>";
        }
    }
?>

<div class="container">
    <div class="row">
        <div>
            <?php if (!empty($error)) : ?>
                <div class="control-group alert alert-danger">
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