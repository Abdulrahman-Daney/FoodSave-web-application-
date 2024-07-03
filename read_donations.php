<!DOCTYPE html>
<html>
    <head>
        <title>Donation Details</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            th {
                background-color: #f2f2f2;
            }
            .edit {
                padding: 5px 10px;
                text-decoration: none;
                border: 1px solid #ddd;
                border-radius: 3px;
                margin: 0 5px;
                background-color: #0077b6;
                color: white;
            }
            .delete {
                padding: 5px 10px;
                text-decoration: none;
                border: 1px solid #ddd;
                border-radius: 3px;
                margin: 0 5px;
                background-color: red;
                color: white;
                cursor: pointer;
            }
        </style>
        <script>
            function confirmDelete(donationId) {
                if (confirm('Are you sure you want to delete this donation?')) {
                    var form = document.createElement('form');
                    form.method = 'post';
                    form.action = 'delete.php';
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'donation_id';
                    input.value = donationId;
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    </head>
    <body>
    
    <?php 
    require('config.php');

    

    $userId = $_SESSION['user_id']; 

    $sql = "SELECT id, food_type, quantity, pickup_time FROM donations WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result) {
            echo "<table>";
            echo "<tr><th>Food Type</th><th>Quantity</th><th>Pickup Time</th><th>Actions</th></tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['food_type']) . "</td>";
                echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                echo "<td>" . htmlspecialchars($row['pickup_time']) . "</td>";
                echo "<td class='action-buttons'>";
                echo "<a class='edit' href='edit.php?id=" . htmlspecialchars($row['id']) . "'>Edit</a>";
                echo "<a class='delete' onclick='confirmDelete(" . htmlspecialchars($row['id']) . ")'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            die("Query failed: " . mysqli_error($conn));
        }

        mysqli_stmt_close($stmt);
    } else {
        die("Query preparation failed: " . mysqli_error($conn));
    }

    mysqli_close($conn);
    ?>

    </body>
</html>
