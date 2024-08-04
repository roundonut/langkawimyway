<?php
require_once('db.php');


// Assuming $_POST['numPeople'] is set from the form submission
$numPeople = $_POST['numPeople'];
$hotelRoomId = $_POST['hotelRoomId'];
$checkInDate = $_POST['checkInDate'];
$checkOutDate = $_POST['checkOutDate'];
$customerId = $_POST['customerId'];
$paymentAmount = $_POST['paymentAmount'];

// Get other necessary data (admin_id) - you need to implement actual logic here

// Random admin_id between 1 and 5
$adminId = rand(1, 5);

// Retrieve room details
$sqlRoom = "SELECT type_name, pricePerNight FROM room WHERE room_No = $hotelRoomId";
$resultRoom = $conn->query($sqlRoom);

if ($resultRoom->num_rows > 0) {
    $row = $resultRoom->fetch_assoc();
    $typeName = $row['type_name'];
    $pricePerNight = $row['pricePerNight'];

    // Calculate the number of days for booking
    $startDate = new DateTime($checkInDate);
    $endDate = new DateTime($checkOutDate);
    $interval = $startDate->diff($endDate);
    $numDays = $interval->days;

    // Calculate payment amount
    $paymentAmount = $pricePerNight * $numDays;

    // Insert into the hotel_booking table
    $sqlInsertBooking = "INSERT INTO hotel_booking (hotel_room_id, customer_id, admin_id, checkindate, checkoutdate, numofpeople, payment_amount)
    VALUES ($hotelRoomId, $customerId, $adminId, '$checkInDate', '$checkOutDate', $numPeople, $paymentAmount)";
    $resultInsertBooking = $conn->query($sqlInsertBooking);

    if ($resultInsertBooking === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Hotel booked! Please book your tour.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Booking failed.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Room not found.']);
}

$conn->close();
?>
