<?php
require('config.php');

session_start();

$userId = $_SESSION['user_id'];
$donationId = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $donationId = $_POST['donation_id'];

    $sql = "DELETE FROM donations WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $donationId, $userId);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Donation deleted successfully.'); window.location.href = 'dashboard.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error deleting donation: " . mysqli_error($conn) . "');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Query preparation failed: " . mysqli_error($conn) . "');</script>";
    }

    mysqli_close($conn);
}

?>
