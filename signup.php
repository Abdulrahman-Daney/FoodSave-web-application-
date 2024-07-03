
<?php session_start()?>
<?php

require 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeatPassword'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    if ($password !== $repeatPassword) {
        echo "Passwords do not match.";
        exit();
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $passwordHash);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
   
    <?php include('header.php') ?>
    <div class="signup-container">
      <form action="signup.php" method="POST">
        <h2>Sign Up</h2>
        <input
          type="text"
          id="username"
          name="username"
          placeholder="Restaurant Name"
          required
        />
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
        <input
          type="password"
          id="repeatPassword"
          name="repeatPassword"
          placeholder="Repeat Password"
          required
        />
        <input type="submit" class="signup" value="Sign Up" />
      </form>
    </div>
    <script src="script.js"></script>
  </body>
</html>

