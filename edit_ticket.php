
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


// Check if the form is submitted for saving flight ticket changes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['flight_save_changes'])) {
    $flight_ticket_id = $_POST['flight_ticket_ID'];
    $payment_amount = $_POST['payment_amount'];
    $numOfPeople = $_POST['numOfPeople'];

    // Update the database with the new values
    $updateSql1 = "UPDATE ticket 
                  SET payment_amount = $payment_amount, 
                      numOfPeople = $numOfPeople
                  WHERE flight_ticket_id = $flight_ticket_id";

    if ($conn->query($updateSql1) === TRUE) {
        // Success
        exit(); // Exit to avoid echoing additional content
    } else {
        // Error handling
        echo "Error updating record: " . $conn->error;
    }
}
?>

<?php
$sql = "SELECT * FROM ticket";
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

<h3>Flight Ticket List</h3>
<table border="1">
    <tr>
        <th>Flight Ticket ID</th>
        <th>Payment Amount</th>
        <th>Number of People</th>
        <th>Customer ID</th>
        <th>Flight ID</th>
        <th>Admin ID</th>
        <th>Action</th>
    </tr>
    <?php
    while ($row1 = $result->fetch_assoc()) {
        echo "<tr data-id='{$row1['flight_ticket_ID']}'>";
        echo "<td>{$row1['flight_ticket_ID']}</td>";
        echo "<td contenteditable>{$row1['payment_amount']}</td>";
        echo "<td contenteditable>{$row1['numOfPeople']}</td>";
        echo "<td>" . ($row1['customer_id'] ? $row1['customer_id'] : 'N/A') . "</td>";
        echo "<td>" . ($row1['flight_id'] ? $row1['flight_id'] : 'N/A') . "</td>";
        echo "<td>" . ($row1['admin_id'] ? $row1['admin_id'] : 'N/A') . "</td>";
        echo "<td><button onclick=\"ticketSaveChanges({$row1['flight_ticket_ID']})\">Save Changes</button> <button onclick=\"deleteRow(this)\">Delete</button></td>";
        echo "</tr>";
    }
    ?>
</table>
</html>



