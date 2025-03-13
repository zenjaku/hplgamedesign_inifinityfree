<div class="container d-flex flex-column align-items-center" id="login">
    <div class="shadow-lg bg-white p-4 rounded-lg d-flex flex-column align-items-center">
        <h1 class="mb-5">LOGIN</h1>
        <?php
        // Generate CSRF token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        ?>
        <form action="server/login.php" method="post" autocomplete="off" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="row d-flex flex-column gap-4">
                <div class="col-md-6 col-sm-12 form-floating">
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username"
                        required>
                    <label for="username">Username</label>
                </div>
                <div class="col-md-6 col-sm-12 form-floating">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password"
                        required>
                    <label for="password">Password</label>
                </div>
            </div>
            <div class="form-buttons mt-4">
                <button type="submit" class="btn btn-dark" name="login">Login</button>
                <button type="reset" class="btn btn-danger">Cancel</button>
            </div>
        </form>
    </div>
</div>