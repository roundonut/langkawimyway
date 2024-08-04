<?php
// Assuming you have a database connection in db.php
require_once('db.php');
session_start();

// Retrieve customer_email from the session
$customer_email = isset($_SESSION['customer_email']) ? $_SESSION['customer_email'] : '';

// Retrieve tour types from the TOUR table
$tour_types = array();
$sql_tour_types = "SELECT DISTINCT tour_type FROM tour";
$result_tour_types = $conn->query($sql_tour_types);

if ($result_tour_types->num_rows > 0) {
    while ($row_tour_types = $result_tour_types->fetch_assoc()) {
        $tour_types[] = $row_tour_types['tour_type'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="#">
    <title>LangkawiMyWay - Tour Booking</title>
</head>

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

    .booking-section {
        text-align: center;
    }

    footer {
        background-color: #34495e;
        color: #fff;
        padding: 10px;
        text-align: center;
        position: fixed;
        bottom: 0;
        width: 100%;
    }

    #booking-container {
    text-align: center;
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    align-items: center; /* Center the form vertically */
}

    #tour-table-container {
        text-align: center;
        margin-top: 20px;
    }

    #tour-table {
        margin: 0 auto;
        border-collapse: collapse;
        width: 80%;
    }

    #tour-table th,
    #tour-table td {
        padding: 12px;
        border: 1px solid #ddd;
    }

    /* Additional styling for buttons */
    button {
        background-color: #3498db;
        color: #fff;
        padding: 10px;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background-color: #2980b9;
    }

</style>

<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="LangkawiMyWay Logo" style="width: 100px;">
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
        <section class="booking-section">
            <h2>List Of Available Tours</h2>
            <!-- Tour booking interface -->
            <div id="booking-container">
                <form id="tour-booking-form" action="#" method="post" onsubmit="return searchTours()">
                    <label for="tour-date">Tour Date:</label>
                    <input type="date" id="tour-date" name="tour-date" required>
                    <label for="quantity-people">Quantity of People:</label>
                    <input type="number" id="quantity-people" name="quantity-people" required>
                    <label for="group-type">Group Type:</label>
                    <select id="group-type" name="group-type" required>
                        <option value="" selected disabled>Select Group Type</option>
                        <?php
                        foreach ($tour_types as $type) {
                            echo "<option value=\"$type\">$type</option>";
                        }
                        ?>
                    </select>
                    <button type="submit">Search</button>
                </form>

                <!-- Tour table display -->
                <div id="tour-table-container" style="display: none;">
                    <!-- The table to display tour information -->
                    <table id="tour-table">
                        <!-- Table headers -->
                        <thead>
                            <tr>
                                <th>Tour Description</th>
                                <th>Tour Duration</th>
                                <th>Tour Cost per Person</th>
                                <th>Tour Type</th>
                                <th>Tour Places</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="tour-table-body">
                            <!-- Tour rows will be displayed here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>For refund, cancel, or changes, please contact our admin at 0318894829.Please make within 7 days prior booking.</p>
    </footer>

    <script>
        function searchTours() {
            // Assuming you have a way to get the date, quantity of people, and group type from the form
            var tourDate = document.getElementById('tour-date').value;

            var date = new Date();
            var year = date.getFullYear();
            var month = (date.getMonth() + 1).toString().padStart(2, '0');
            var day = date.getDate().toString().padStart(2, '0');
            var today = `${year}-${month}-${day}`;
            
            if (tourDate < today) {
                alert("The check-in date must be today or later!");
                return false;
            }

            var quantityPeople = document.getElementById('quantity-people').value;
            if (quantityPeople < 0) {
            alert("The number of people cannot be negative!");
            return false;
        }
            var groupType = document.getElementById('group-type').value;

            // Use AJAX to send a request to search-tours.php with the selected parameters
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    console.log("Status: " + xhr.status);
                    console.log("Response: " + xhr.responseText);
                    // Handle the response and populate the tour table if needed
                    var tourTableBody = document.getElementById('tour-table-body');
                    tourTableBody.innerHTML = xhr.responseText;

                    // Show the tour table container
                    document.getElementById('tour-table-container').style.display = 'block';
                }
            };

            var params = "tourDate=" + encodeURIComponent(tourDate) +
                "&quantityPeople=" + encodeURIComponent(quantityPeople) +
                "&groupType=" + encodeURIComponent(groupType);

            xhr.open("POST", "search-tours.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(params);

            return false; // Prevent form submission
        }

        function bookTour(tourId, tourCostPerPerson) {
            // Assuming you have a way to get the date, quantity of people, and admin ID from the form
            var tourDate = document.getElementById('tour-date').value;
            var quantityPeople = document.getElementById('quantity-people').value;

            // Generate a random admin ID between 1 and 5
            var adminId = Math.floor(Math.random() * 5) + 1;

            // Calculate payment amount
            var paymentAmount = quantityPeople * tourCostPerPerson;

            // Use AJAX to send a request to book-tour.php with the selected parameters
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    console.log("Status: " + xhr.status);
                    console.log("Response: " + xhr.responseText);
                    // Handle the response if needed
                    alert("Tour booked! Please checkout to make payment.");
                    window.location.href = 'checkout.php';

                    // You can redirect to the checkout page or perform other actions here
                }
            };

            var params = "tourId=" + encodeURIComponent(tourId) +
                "&tourDate=" + encodeURIComponent(tourDate) +
                "&quantityPeople=" + encodeURIComponent(quantityPeople) +
                "&adminId=" + encodeURIComponent(adminId) +
                "&paymentAmount=" + encodeURIComponent(paymentAmount);

            xhr.open("POST", "book-tour.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(params);

            return false; // Prevent default link behavior
        }

        function redirectToLoginPage() {
            console.log("redirectToLoginPage function called");
            window.location.href = 'login.php'; // Redirect to login.php
        }

        function redirectToCheckout() {
            window.location.href = 'checkout.php';
        }

        function redirectToAdmin() {
            window.location.href = 'admin_index.php';
        }

    </script>
    
</body>

</html>
