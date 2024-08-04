<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_tab.css">
</head>
<body>
    <div class="navbar">
        <a href="edit_ticket.php" onclick="openTab('editTicket')">Edit Flight Ticket</a>
        <a href="edit_hotel_booking.php" onclick="openTab('editHotelBooking')">Edit Hotel Booking</a>
        <a href="edit_tour_booking.php" onclick="openTab('editTourBooking')">Edit Tour Booking</a>
        <a href="index.php">Exit</a>
    </div>

    <div class="dashboard-container">
        
        <div class="quote">
            Celebrating the LangkawiMyWay Dream Team! Every booking, every smile, every adventure - you make it happen ;)
        </div>

    </div>
</body>
</html>

<?php

require_once('admin_function.php');

// Check if the form is submitted for saving changes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tour_save_changes'])) {
    $tour_booking_ID = $_POST['tour_booking_ID'];
    $booking_date =  date('Y-m-d', strtotime($_POST['booking_date']));
    $payment_amount = $_POST['payment_amount'];
    $booking_quantity_people = $_POST['booking_quantity_people'];

    // Update the database with the new values
    $updateSql3 = "UPDATE tour_booking
                  SET booking_date = '$booking_date', 
                      payment_amount = $payment_amount, 
                      booking_quantity_people = $booking_quantity_people
                  WHERE tour_booking_ID = $tour_booking_ID";

    if ($conn->query($updateSql3) === TRUE) {
        // Success
        exit(); // Exit to avoid echoing additional content
    } else {
        // Error handling
        echo "Error updating record: " . $conn->error;
    }
}

$sql = "SELECT * FROM tour_booking";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<style>
    table {
        margin: auto;
    }    
    body {
        text-align: center;
    }
</style>

<h3>Tour Booking List</h3>
<table border="1">
    <tr>
        <th>Tour Booking ID</th>
        <th>Booking Date</th>
        <th>Payment Amount</th>
        <th>Booking Quantity People</th>
        <th>Tour ID</th>
        <th>Customer ID</th>
        <th>Admin ID</th>
        <th>Action</th>
    </tr>
    <?php
    while ($row3 = $result->fetch_assoc()) {
        echo "<tr data-id='{$row3['tour_booking_ID']}'>";
        echo "<td>{$row3['tour_booking_ID']}</td>";
        echo "<td contenteditable>{$row3['booking_date']}</td>";
        echo "<td contenteditable>{$row3['payment_amount']}</td>";
        echo "<td contenteditable>{$row3['booking_quantity_people']}</td>";
        echo "<td>{$row3['tour_ID']}</td>";
        echo "<td>{$row3['customer_ID']}</td>";
        echo "<td>{$row3['admin_ID']}</td>";
        echo "<td><button onclick=\"tourSaveChanges({$row3['tour_booking_ID']})\">Save Changes</button> <button onclick=\"deleteRow(this)\">Delete</button></td>";
        echo "</tr>";
    }
    ?>
</table>
</html>