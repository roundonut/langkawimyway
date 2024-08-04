<?php
require_once('db.php');
session_start();

// Retrieve customer_id from the session or redirect to login if not logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Retrieve booking information based on customer_id
$sql = "SELECT flight_ticket_id, payment_amount FROM ticket WHERE customer_id = $customer_id";
$result = $conn->query($sql);
$flightBooking = ($result->num_rows > 0) ? $result->fetch_assoc() : null;

$sql = "SELECT hotel_booking_id, payment_amount FROM hotel_booking WHERE customer_id = $customer_id";
$result = $conn->query($sql);
$hotelBooking = ($result->num_rows > 0) ? $result->fetch_assoc() : null;

$sql = "SELECT tour_booking_id, payment_amount FROM tour_booking WHERE customer_id = $customer_id";
$result = $conn->query($sql);
$tourBooking = ($result->num_rows > 0) ? $result->fetch_assoc() : null;

$totalAmount = 0;

if ($flightBooking !== null) {
    $totalAmount += $flightBooking['payment_amount'];
}

if ($hotelBooking !== null) {
    $totalAmount += $hotelBooking['payment_amount'];
}

if ($tourBooking !== null) {
    $totalAmount += $tourBooking['payment_amount'];
}

$customer_email = isset($_SESSION['customer_email']) ? $_SESSION['customer_email'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>LangkawiMyWay - Checkout</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #3498db;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav {
            display: flex;
            justify-content: center;
            align-items: center;
                    }

            nav ul {
                list-style: none;
                display: flex;
            }

            nav ul li {
                margin-right: 20px;
            }

            nav a {
                text-decoration: none;
                color: #fff;
                font-weight: bold;
            }

        main {
            padding: 20px;
        }

        .checkout-section {
            text-align: center;
            margin-top: 20px;
        }

        #checkout-container {
            text-align: center;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center the form vertically */
        }

        #payment-form {
            margin-top: 20px;
            text-align: middle;
            width: 50%;
            margin: auto;
        }

        #total-amount {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .user-actions {
            margin-left: auto; /* Push the user-related elements to the right */
            display: flex;
            align-items: center;
            margin-top: 10px; /* Add margin to separate from other elements */
        }

        .user-actions > * {
            margin-left: 10px;
        }

        .user-actions > span {
            margin-right: auto; /* Push other elements to the right */
        }

        button {
            background-color: #3498db;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">
            <img src="/logo.png" alt="LangkawiMyWay Logo" style="width: 100px;">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Flight</a></li>
                <li><a href="hotel.php">Hotel</a></li>
                <li><a href="tour.php">Tour</a></li>
            </ul>
        </nav>
        <div class="user-actions">
            <span>Welcome, <?= $customer_email ?></span>
            <button id="checkout" onclick="redirectToCheckout()">Checkout</button>
            <button id="admin-login" onclick="redirectToAdmin() ">Admin</button>
            <button id="user-login" onclick="redirectToLoginPage()">User</button>
        </div>
    </header>

    <main>
    <section class="checkout-section">
        <h2>Checkout</h2>

        <!-- Checkout container -->
        <div id="checkout-container">
            <!-- Display information from TICKET table -->
            <div class="booking-details">
            <p>Customer ID: <?php echo isset($customer_id) ? $customer_id : ''; ?></p>
            </div>

            <div class="booking-details">
                <h3>Flight Booking</h3>
                <p>Ticket ID: <?php echo isset($flightBooking['flight_ticket_id']) ? $flightBooking['flight_ticket_id'] : ''; ?></p>
                <p>Payment Amount: <?php echo isset($flightBooking['payment_amount']) ? number_format($flightBooking['payment_amount'], 2) : ''; ?></p>

            </div>

            <!-- Display information from HOTEL_BOOKING table -->
            <div class="booking-details">
                <h3>Hotel Booking</h3>
                <p>Booking ID: <?php echo isset($hotelBooking['hotel_booking_id']) ? $hotelBooking['hotel_booking_id'] : ''; ?></p>
                <p>Payment Amount: <?php echo isset($hotelBooking['payment_amount']) ? number_format($hotelBooking['payment_amount'], 2) : ''; ?></p>
            </div>

            <!-- Display information from TOUR_BOOKING table -->
            <div class="booking-details">
                <h3>Tour Booking</h3>
                <p>Booking ID: <?php echo isset($tourBooking['tour_booking_id']) ? $tourBooking['tour_booking_id'] : ''; ?></p>
                <p>Payment Amount: <?php echo isset($tourBooking['payment_amount']) ? number_format($tourBooking['payment_amount'], 2) : ''; ?></p>
            </div>

            <!-- Display total amount -->
            <div id="total-amount">
                Total Amount: <?php echo isset($totalAmount) ? number_format($totalAmount, 2) : ''; ?>
            </div>

            <!-- Payment form -->
            <form id="payment-form" action="process_payment.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                
                <!-- Include customer_id in the form -->
                <input type="hidden" name="customer_id" value="<?php echo isset($customer_id) ? $customer_id : ''; ?>">
                
                <input type="hidden" name="flightTicketId" value="<?php echo isset($flightBooking['flight_ticket_id']) ? $flightBooking['flight_ticket_id'] : ''; ?>">
                <input type="hidden" name="hotelBookingId" value="<?php echo isset($hotelBooking['hotel_booking_id']) ? $hotelBooking['hotel_booking_id'] : ''; ?>">
                <input type="hidden" name="tourBookingId" value="<?php echo isset($tourBooking['tour_booking_id']) ? $tourBooking['tour_booking_id'] : ''; ?>">
                <input type="hidden" name="totalAmount" value="<?php echo isset($totalAmount) ? $totalAmount : ''; ?>">
                
                <label for="card-number">Card Number:</label>
                <input type="text" id="card-number" name="cardNumber" required>
                <button type="submit" onclick="submitPayment()">Pay</button>
            </form>
        </div>
    </section>
</main>

    <script>
    function submitPayment() {
    var name = document.getElementById('name').value;
    var cardNumber = document.getElementById('card-number').value;

    // Assuming you have a way to get flight_ticket_id, hotel_booking_id, and tour_booking_id
    var flightTicketId = <?php echo $flightBooking['flight_ticket_id']; ?>;
    var hotelBookingId = <?php echo $hotelBooking['hotel_booking_id']; ?>;
    var tourBookingId = <?php echo $tourBooking['tour_booking_id']; ?>;
    
    // Assuming you have a way to get the total amount
    var totalAmount = <?php echo $totalAmount; ?>;

    // Use AJAX to send a request to the server to process the payment
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Payment successful
                var response = JSON.parse(xhr.responseText);
                alert(response.message);
                window.location.href = 'index.php';
            } else {
                // Payment failed
                alert("Payment failed. Please try again.");
            }
        }
    };

    var params = "name=" + encodeURIComponent(name) +
        "&flightTicketId=" + encodeURIComponent(flightTicketId) +
        "&hotelBookingId=" + encodeURIComponent(hotelBookingId) +
        "&tourBookingId=" + encodeURIComponent(tourBookingId) +
        "&totalAmount=" + encodeURIComponent(totalAmount) +

    xhr.open("POST", "process_payment.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(params);
}


        function redirectToCheckout() {
            window.location.href = 'checkout.php';
        }

        function redirectToAdmin() {
            window.location.href = 'admin_index.php';
        }

        function redirectToLoginPage() {
            window.location.href = 'login.php'; // Redirect to login.php
        }

    </script>
</body>

</html>
