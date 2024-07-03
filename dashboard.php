<?php
require 'config.php';

session_start();
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

$user_id = $_SESSION['user_id'];

// Fetch user information and initial pickup history
if (isset($_GET['action']) && $_GET['action'] == 'get_user_details') {
    $userStmt = $conn->prepare("SELECT username, created_at FROM users WHERE id = ?");
    $userStmt->bind_param("i", $user_id);
    $userStmt->execute();
    $userStmt->bind_result($username, $created_at);
    $userStmt->fetch();
    $userStmt->close();

    $historyStmt = $conn->prepare("SELECT pickup_time FROM donations WHERE user_id = ?");
    $historyStmt->bind_param("i", $user_id);
    $historyStmt->execute();
    $historyResult = $historyStmt->get_result();
    $pickupDates = $historyResult->fetch_all(MYSQLI_ASSOC);
    $historyStmt->close();

    echo json_encode([
        'success' => true,
        'username' => $username,
        'created_at' => $created_at,
        'pickupHistory' => $pickupDates
    ]);
    exit();
}

// Handle donation form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['foodType']) && isset($_POST['quantity']) && isset($_POST['pickupTime'])) {
    $foodType = htmlspecialchars($_POST['foodType']);
    $quantity = htmlspecialchars($_POST['quantity']);
    $pickupTime = htmlspecialchars($_POST['pickupTime']);

    $donationStmt = $conn->prepare("INSERT INTO donations (user_id, food_type, quantity, pickup_time) VALUES (?, ?, ?, ?)");
    if ($donationStmt === false) {
        echo json_encode([
            'success' => false,
            'error' => 'Prepare failed: ' . htmlspecialchars($conn->error)
        ]);
        exit();
    }
    $donationStmt->bind_param("isss", $user_id, $foodType, $quantity, $pickupTime);

    if ($donationStmt->execute()) {
        // Fetch updated pickup history
        $historyStmt = $conn->prepare("SELECT pickup_time FROM donations WHERE user_id = ?");
        $historyStmt->bind_param("i", $user_id);
        $historyStmt->execute();
        $historyResult = $historyStmt->get_result();
        $pickupDates = $historyResult->fetch_all(MYSQLI_ASSOC);
        $historyStmt->close();

        echo json_encode([
            'success' => true,
            'pickupTime' => $pickupTime,
            'pickupHistory' => $pickupDates
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Execute failed: ' . htmlspecialchars($donationStmt->error)
        ]);
    }

    $donationStmt->close();
} else {
    // echo json_encode([
    //     'success' => false,
    //     'error' => 'Invalid request'
    // ]);
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Food Save</title>
    <link rel="stylesheet" href="styles.css" />
    <!-- <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    /> -->
  </head>
  <body>
    <?php include('header.php') ?>
    <main>
      <section class="dashboard-section">
        <h2>Welcome <?php echo $_SESSION['username']; ?> to Food Save Dashboard</h2>
        <p>From here you can manage your surplus food donations and more.</p>

        </ul>

        <h3>Donate Food</h3>
        <form
          id="donationForm"
          onsubmit="showDonationConfirmation(); return false;"
        >
          <div class="form-group">
            <label for="foodType">Food Type:</label>
            <input type="text" id="foodType" name="foodType" required />
          </div>
          <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="text" id="quantity" name="quantity" required />
          </div>
          <div class="form-group">
            <label for="pickupTime">Preferred Pickup Time:</label>
            <input
              type="datetime-local"
              id="pickupTime"
              name="pickupTime"
              required
            />
          </div>
          <button type="submit" class="btn-primary">Donate</button>
        </form>
        <h3>Manage your donations</h3>
        <?php include('read_donations.php') ?>
      </section>
    </main>
    <footer>
      <p>&copy; 2024 Food Save. All rights reserved.</p>
    </footer>

    <!-- Donation Confirmation Modal -->
    <div id="donationConfirmationModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeDonationConfirmation()">&times;</span>
        <p>
          Donation successful! We are sending a truck to your location at the
          chosen date: <span id="modalPickupTime"></span>.
        </p>
      </div>
    </div>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Fetch user details and initial pickup history
        fetch("dashboard.php?action=get_user_details")
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              document.getElementById(
                "user-details"
              ).innerText = `Username: ${data.username}, Joined on: ${data.created_at}`;
              updatePickupHistory(data.pickupHistory);
            } else {
              console.error("Failed to load user details:", data.error);
            }
          })
          .catch((error) => console.error("Error:", error));
      });

      document
        .getElementById("donationForm")
        .addEventListener("submit", function (event) {
          event.preventDefault();
          const formData = new FormData(this);
          fetch("dashboard.php", {
            method: "POST",
            body: formData,
          })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                document.getElementById("modalPickupTime").innerText =
                  data.pickupTime;
                document.getElementById(
                  "donationConfirmationModal"
                ).style.display = "block";
                updatePickupHistory(data.pickupHistory);
              } else {
                alert("Donation failed: " + data.error);
              }
            })
            .catch((error) => console.error("Error:", error));
        });

      function updatePickupHistory(history) {
        const historyElement = document.getElementById("pickup-history");
        historyElement.innerHTML = "";
        history.forEach((item) => {
          const li = document.createElement("li");
          li.textContent = `Pickup on ${item.pickup_time}`;
          historyElement.appendChild(li);
        });
      }

      function showDonationConfirmation() {
        const pickupTime = document.getElementById("pickupTime").value;
        if (pickupTime) {
          document.getElementById("modalPickupTime").innerText = pickupTime;
          document.getElementById("donationConfirmationModal").style.display =
            "block";
        }
      }

      function closeDonationConfirmation() {
        document.getElementById("donationConfirmationModal").style.display =
          "none";
      }

      window.onclick = function (event) {
        const modal = document.getElementById("donationConfirmationModal");
        if (event.target == modal) {
          modal.style.display = "none";
        }
      };
    </script>
  </body>
</html>
