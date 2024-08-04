
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


// Check if the form is submitted for saving hotel booking changes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hotel_save_changes'])) {
    $hotel_booking_ID = $_POST['hotel_booking_ID'];
    $checkInDate = date('Y-m-d', strtotime($_POST['checkInDate'])); // Format check-in date
    $checkOutDate = date('Y-m-d', strtotime($_POST['checkOutDate'])); // Format check-out date
    $numOfPeople = $_POST['numOfPeople'];
    $payment_amount = $_POST['payment_amount'];

    // Update the database with the new values
    $updateSql1 = "UPDATE hotel_booking
                  SET checkInDate = '$checkInDate', 
                      checkOutDate = '$checkOutDate', 
                      numOfPeople = $numOfPeople,
                      payment_amount = $payment_amount
                  WHERE hotel_booking_ID = $hotel_booking_ID";

    if ($conn->query($updateSql1) === TRUE) {
        // Success
        var_dump("Successfully edited!");
        exit();
        
    } else {
        // Error handling
        echo "Error updating record: " . $conn->error;
    }
    
}

$sql = "SELECT * FROM hotel_booking";
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

<h3>Hotel Booking List</h3>
<table border="1">
    <tr>
        <th>Booking ID</th>
        <th>Hotel Room ID</th>
        <th>Customer ID</th>
        <th>Admin ID</th>
        <th>Check-In Date</th>
        <th>Check-Out Date</th>
        <th>Number of People</th>
        <th>Payment Amount</th>
        <th>Action</th>
    </tr>

    <?php
    while ($row2 = $result->fetch_assoc()) {
        echo "<tr data-id='{$row2['hotel_booking_ID']}'>";
        echo "<td>{$row2['hotel_booking_ID']}</td>";
        echo "<td>{$row2['hotel_room_ID']}</td>"; // Update column name
        echo "<td>{$row2['customer_ID']}</td>";
        echo "<td>{$row2['admin_ID']}</td>";
        echo "<td contenteditable>{$row2['checkInDate']}</td>";
        echo "<td contenteditable>{$row2['checkOutDate']}</td>";
        echo "<td contenteditable>{$row2['numOfPeople']}</td>";
        echo "<td contenteditable>{$row2['payment_amount']}</td>";
        echo "<td><button onclick=\"hotelSaveChanges({$row2['hotel_booking_ID']})\">Save Changes</button> <button onclick=\"deleteRow(this)\">Delete</button></td>";
        echo "</tr>";
    }
?>
</table>
</html>

