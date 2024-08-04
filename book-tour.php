<?php
// Assuming you have a database connection in db.php
require_once('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve parameters from the AJAX request
    $tourId = $_POST['tourId'];
    $tourDate = $_POST['tourDate'];
    $quantityPeople = $_POST['quantityPeople'];
    $adminId = $_POST['adminId'];
    $paymentAmount = $_POST['paymentAmount'];
    $customerId = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : '';

    // Validate and sanitize the input as needed

    // Insert the tour booking record into the database
    $sql = "INSERT INTO tour_booking (booking_date, payment_amount, booking_quantity_people, tour_ID, customer_ID, admin_ID) 
            VALUES ('$tourDate', $paymentAmount, $quantityPeople, $tourId, $customerId, $adminId)";

    if ($conn->query($sql)) {
        $response = array('success' => true, 'message' => 'Tour booked successfully! Please checkout to make payment.');
    } else {
        $response = array('success' => false, 'message' => 'Error booking the tour: ' . $conn->error);
    }

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Handle invalid request method if needed
    echo 'Invalid request method';
}
?>
