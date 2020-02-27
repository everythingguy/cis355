<?php require "partial/header.php"; ?>

<?php
    loggedin();

    if (isset($_GET["remove"]) && $_GET["remove"] == true) {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE users set picture = ? WHERE id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array(null, $_SESSION['user_ID']));
        Database::disconnect();

        unset($_SESSION["picture"]);
    } elseif (count($_FILES) > 0) {
        $error = null;

        $file_type = $_FILES['picture']['type'];
        $allowed = array("image/jpeg", "image/gif", "image/png");

        if ($_FILES['picture']['tmp_name'] == null)
            $error = $error . "<li>Please Select a Picture</li>";
        elseif (!in_array($file_type, $allowed))
            $error = $error . '<li>Only jpg, gif, and png files are allowed.</li>';
        else {
            $image = $_FILES['picture']['tmp_name'];
            $name = $_FILES['picture']['name'];
            $image = file_get_contents($image);

            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE users set picture = ? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($image, $_SESSION['user_ID']));
            Database::disconnect();

            $_SESSION["picture"] = base64_encode($image);
        }
    }
?>

<div class="container">
    <div class="row">
        <?php
            if (isset($_SESSION["picture"])) {
                echo "<img height='250px' width='250px' src='data:image/;base64," . $_SESSION["picture"] . "'/>";
            }
        ?>
        <?php if (!empty($error)) : ?>
            <div class="control-group error">
                <div class="controls">
                    <span class="help-inline">
                        <ul><?php echo $error; ?></ul>
                    </span>
                </div>
            </div>
        <?php endif; ?>
        <form class="form-horizontal" method="post" action="upload.php" enctype="multipart/form-data">
            <div class="control-group">
                <label class="control-label">Profile Picture</label>
                <div class="controls">
                    <input name="picture" type="file">
                </div>
            </div>
            <div class="form-actions">
                <a class="btn" href="index.php">Back</a>
                <button class="btn btn-danger" onclick="location.href='upload.php?remove=true'; return false;">Remove</button>
                <button type="submit" class="btn btn-success">Upload</button>
            </div>
        </form>
    </div>
</div>

<?php require "partial/footer.php"; ?>