
<?php
require 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    $stmt = $conn->prepare("SELECT id, username, password_hash FROM users WHERE email = ?");
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }  

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($userId, $username, $passwordHash);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $passwordHash)) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: login.php?error=Invalid email or password");
            exit();
        }
    } else {
        header("Location: login.php?error=Invalid email or password");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // echo "Invalid request method.";
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <?php include('header.php') ?>
    <div class="login-container">
      <form action="login.php" method="POST">
        <h2>Login</h2>
        <input
          type="email"
          id="email"
          name="email"
          placeholder="Email"
          required
        />
        <input
          type="password"
          id="password"
          name="password"
          placeholder="Password"
          required
        />
        <input type="submit" class="login" value="Login" />
      </form>
    </div>
    <script src="script.js"></script>
  </body>
</html>

