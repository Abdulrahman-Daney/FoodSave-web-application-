<?php
require('config.php');

session_start();



$userId = $_SESSION['user_id'];
$donationId = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $food_type = $_POST['food_type'];
    $quantity = $_POST['quantity'];
    $pickup_time = $_POST['pickup_time'];

    $sql = "UPDATE donations SET food_type = ?, quantity = ?, pickup_time = ? WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sisii", $food_type, $quantity, $pickup_time, $donationId, $userId);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Donation updated successfully.'); window.location.href = 'dashboard.php';</script>";
        } else {
            echo "<script>alert('Error updating donation: " . mysqli_error($conn) . "');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Query preparation failed: " . mysqli_error($conn) . "');</script>";
    }

    mysqli_close($conn);
} else {
    $sql = "SELECT food_type, quantity, pickup_time FROM donations WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $donationId, $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
        } else {
            die("Query failed: " . mysqli_error($conn));
        }
    } else {
        die("Query preparation failed: " . mysqli_error($conn));
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Donation</title>
    <style>
        .popup {
            display: block;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .popup-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
    </style>
</head>
<body>
    <div id="editPopup" class="popup">
        <div class="popup-content">
            <h2>Edit Donation</h2>
            <form method="post">
                <label for="food_type">Food Type:</label><br>
                <input type="text" id="food_type" name="food_type" value="<?php echo htmlspecialchars($row['food_type']); ?>"><br>
                <label for="quantity">Quantity:</label><br>
                <input type="text" id="quantity" name="quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>"><br>
                <label for="pickup_time">Pickup Time:</label><br>
                <input type="text" id="pickup_time" name="pickup_time" value="<?php echo htmlspecialchars($row['pickup_time']); ?>"><br><br>
                <input type="submit" value="Update">
                <button type="button" onclick="window.location.href='dashboard.php'">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>
