<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: dashboard.php");
    exit;
}

require_once "config.php";

$username = $password = "";
$username_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password FROM users WHERE username = :username";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            $param_username = trim($_POST["username"]);

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        if (password_verify($password, $hashed_password)) {
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            header("location: dashboard.php");
                        } else {
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else {
                    $login_err = "Invalid username or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            unset($stmt);
        }
    }
    unset($pdo);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        font: 14px sans-serif;
        background-color: #121212; /* Dark background for a sleek, modern look */
        color: #ffffff; /* White text for contrast */
    }
    .wrapper {
        width: 360px;
        padding: 20px;
        background-color: #1e1e1e; /* Slightly darker background for the wrapper */
        border-radius: 8px;
        box-shadow: 0px 0px 15px rgba(255, 0, 0, 0.3); /* Red glow for modern futuristic look */
        margin: auto;
        margin-top: 100px;
    }
    h2, p {
        color: #ff0000; /* Neon red for headings */
    }
    .form-control {
        background-color: #333333; /* Dark background for input fields */
        color: #ffffff; /* White text in input fields */
        border: 1px solid #ff0000; /* Neon red border */
    }
    .form-control:focus {
        border-color: #ff0000; /* Neon red border on focus */
        box-shadow: 0 0 8px rgba(255, 0, 0, 0.5); /* Red glow on focus */
    }
    .btn-primary {
        background-color: #ff4500; /* Neon orange-red button for vibrancy */
        border: none;
    }
    .btn-primary:hover {
        background-color: #ff6347; /* Warm coral on hover for a nice contrast */
    }
    .alert-danger {
        background-color: #ff6347; /* Warm coral alert for a pop of color */
        color: #ffffff;
        border: none;
    }
    a {
        color: #ff4500; /* Neon orange-red links for a cool, futuristic feel */
    }
    a:hover {
        color: #ff0000; /* Neon red on hover for smooth transition */
    }
</style>



</head>
<body>
    <div class="wrapper" id="loginWrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a id="registerLink">Sign up now</a>.</p>
        </form>
    </div>

    <script>
        document.getElementById("registerLink").addEventListener("click", function(event) {
            event.preventDefault();
            const loginWrapper = document.getElementById("loginWrapper");
            loginWrapper.classList.add("fade-out");
            setTimeout(function() {
                window.location.href = "register.php";
            }, 500); 
        });
    </script>
</body>
</html>