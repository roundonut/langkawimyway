
<?php
require_once('db.php');
session_start();

// Assuming $_POST['num-people'] is set from the form submission
$numPeople = $_POST['num-people'];

// Get customer_id from the session
if (isset($_SESSION['customer_id'])) {
    $customerId = $_SESSION['customer_id'];
} else {
    // Redirect or handle the case where customer_id is not available
    echo json_encode(['success' => false, 'message' => 'Customer not logged in.']);
    exit;
}

// Get other necessary data (admin_id) - you need to implement actual logic here

// Random admin_id between 1 and 5
$adminId = rand(1, 5);

// Assuming you have flight_id from the button click
$flightId = $_POST['flightId'];

// Retrieve flight details
$sqlFlight = "SELECT price FROM flight WHERE flight_id = $flightId";
$resultFlight = $conn->query($sqlFlight);

if ($resultFlight->num_rows > 0) {
    $row = $resultFlight->fetch_assoc();
    $price = $row['price'];

    // Calculate payment amount
    $paymentAmount = $numPeople * $price;

    // Insert into the ticket table
    $sqlInsertTicket = "INSERT INTO ticket (payment_amount, numOfPeople, customer_id, flight_id, admin_id)
                        VALUES ($paymentAmount, $numPeople, $customerId, $flightId, $adminId)";
    $resultInsertTicket = $conn->query($sqlInsertTicket);

    if ($resultInsertTicket === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Flight booked! Please book your hotel.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Booking failed.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Flight not found.']);
}

$conn->close();
?>