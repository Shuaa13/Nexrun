<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'metadata.php'; ?>
</head>

<body>

    <div class="container">
        <div class="row my-5">
            <div class="col-md-5 mx-auto">
                <div class="text-center mb-5">
                    <a href="index.php">
                        <img src="resources/images/logo.png" class="img-fluid" style="max-height: 40px;">
                    </a>
                </div>
                <?php if (isset($_SESSION['msg'])): ?>
                    <div class="alert <?php echo $_SESSION['alert']; ?>" role="alert">
                        <?php echo $_SESSION['msg']; ?>
                    </div>
                    <?php 
                        unset($_SESSION['msg']);
                        unset($_SESSION['alert']);
                    ?>
                <?php endif; ?>
                <form action="database/db_login.php" method="POST" class="bg-white-op py-4 px-5 rounded border">
                 
                    <h1 class="my-3">Login</h1>
                    <div class="form-group mb-3">
                        <label class="text-dark">Username</label>
                        <input type="text" class="form-control" name="username" autocomplete="off" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-dark">Password</label>
                        <input type="password" class="form-control password" name="password" autocomplete="off" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input showpassword" type="checkbox" value="">
                        <label class="form-check-label">
                            Show Password
                        </label>
                    </div>
                    <button type="submit" class="btn btn-success rounded-pill w-100 my-4">Login</button>
                    <div class="mb-3">
                        <p class="text-center">No account yet?&nbsp;
                            <a href="signup.php" class="text-success text-decoration-none">Signup</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <?php include 'footer.php'; ?>
    
</body>

</html>