<?php

require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the POST request
    $name = $_POST['name'];
    $flightTicketId = $_POST['flightTicketId'];
    $hotelBookingId = $_POST['hotelBookingId'];
    $tourBookingId = $_POST['tourBookingId'];
    $totalAmount = $_POST['totalAmount'];

    // You should perform additional validation and sanitation of the input data

    // Check if the flight, hotel, or tour has already been paid for the given customer
    $checkPaymentStatusSql = "SELECT * FROM booking_master WHERE (flight_ticket_id = '$flightTicketId'
        AND hotel_booking_id = '$hotelBookingId' AND tour_booking_Id = '$tourBookingId') AND payment_status = 'paid'";

    $result = $conn->query($checkPaymentStatusSql);

    if ($flightTicketId <= 0 || $hotelBookingId <= 0 || $tourBookingId <= 0 || $totalAmount <= 0) {
        echo json_encode(['success' => true, 'message' => 'Please complete your booking on flight/hotel/tour.']);
        echo '<script>window.location.replace("checkout.php"); alert("Please complete your booking on flight/hotel/tour.");</script>';
        exit();
    }

    if ($result->num_rows > 0) {
        // Already paid, output a message
        echo json_encode(['success' => false, 'message' => 'You have already paid for this booking.']);
        echo '<script>window.location.replace("index.php"); alert("You have already paid for this booking.");</script>';
        exit();
    }

    // Adjusted the casting using COALESCE to provide a default value of 0
    $insertSql = "INSERT INTO booking_master (flight_ticket_id, hotel_booking_id, tour_booking_Id, cardName, payment_status, paymentDate, totalAmount)
                  VALUES ('$flightTicketId', '$hotelBookingId', '$tourBookingId', '$name', 'paid', NOW(), '$totalAmount')";

    if ($conn->query($insertSql) === TRUE) {
        // Payment successful
        // Output a success message
        echo json_encode(['success' => true, 'message' => 'Payment Successful! Happy Holiday!.']);
        echo '<script>window.location.replace("index.php"); alert("Payment Successful! Happy Holiday!");</script>';
    } else {
        // Payment failed 
        echo json_encode(['success' => false, 'message' => 'Payment failed.']);
        echo '<script>window.location.replace("checkout.php"); alert("Payment Failed!");</script>';
        exit();
    }
} else {
    // Invalid request method
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Payment not found.']);
    exit();
}
?>
