<?php
// Assuming you have a database connection in db.php
require_once('db.php');
session_start();

// Retrieve customer_email from the session
$customer_email = isset($_SESSION['customer_email']) ? $_SESSION['customer_email'] : '';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="#">
    <title>LangkawiMyWay - Hotel Booking</title>
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
}

#hotel-table-container {
    text-align: center;
    margin-top: 20px;
}

#hotel-table {
    margin: 0 auto;
    border-collapse: collapse;
    width: 80%; /* Adjust the width of the table */
}

#hotel-table th, #hotel-table td {
    padding: 12px;
    border: 1px solid #ddd; /* Add borders to cells for better visibility */
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
            <h2>List Of Available Hotels</h2>
            <!-- Hotel booking interface -->
            <div id="booking-container">
                <form id="hotel-booking-form" action="book-hotel.php" method="post" onsubmit="return showHotelTable()">
                    <label for="num-people">Number of People:</label>
                    <input type="number" id="num-people" name="num-people" required>
                    <label for="check-in-date">Check-In Date:</label>
                    <input type="date" id="check-in-date" name="check-in-date" required>
                    <label for="check-out-date">Check-Out Date:</label>
                    <input type="date" id="check-out-date" name="check-out-date" required>
                    <button type="submit">Search</button>
                </form>

                <!-- Hotel table display -->
                <div id="hotel-table-container" style="display: none;">
                    <!-- The table to display hotel and room information -->
                    <table id="hotel-table">
                        <!-- Table headers -->
                        <thead>
                            <tr>
                                <th>Hotel Name</th>
                                <th>Address</th>
                                <th>Phone No</th>
                                <th>Email</th>
                                <th>Star Rating</th>
                                <th>Room Type</th>
                                <th>Price Per Night</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="hotel-table-body">
                            <?php
                            // Retrieve hotels and rooms data from the database
                            $sql = "SELECT h.hotel_ID, h.hotelName, h.address, h.phoneNo, h.email, h.starRating,
                                    r.hotel_room_ID, rm.type_name, rm.pricePerNight, rm.description
                                    FROM hotel h
                                    INNER JOIN hotel_room r ON h.hotel_ID = r.hotel_ID
                                    INNER JOIN room rm ON r.room_ID = rm.room_No";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $row['hotelName'] . '</td>';
                                    echo '<td>' . $row['address'] . '</td>';
                                    echo '<td>' . $row['phoneNo'] . '</td>';
                                    echo '<td>' . $row['email'] . '</td>';
                                    echo '<td>' . $row['starRating'] . '</td>';
                                    echo '<td>' . $row['type_name'] . '</td>';
                                    echo '<td>' . $row['pricePerNight'] . '</td>';
                                    echo '<td>' . $row['description'] . '</td>';
                                    echo '<td><button class="book-hotel-button" onclick="bookHotel(' . $row['hotel_room_ID'] . ', ' . $row['pricePerNight'] . ')">Book Hotel</button></td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="8">No hotels available</td></tr>';
                            }
                            ?>
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
    function showHotelTable() {
        document.getElementById('hotel-table-container').style.display = 'block';
        return false; // Prevent form submission
    }

    function bookHotel(hotelRoomId, pricePerNight) {
        console.log("Hotel Room ID: " + hotelRoomId);

        // Assuming you have a way to get the number of people, check-in, and check-out dates from the form
        var numPeople = document.getElementById('num-people').value;
        
        if (numPeople < 0) {
            alert("The number of people cannot be negative!");
            return false;
        }
        var checkInDate = document.getElementById('check-in-date').value;
        var checkOutDate = document.getElementById('check-out-date').value;
        var date = new Date();
        var year = date.getFullYear();
        var month = (date.getMonth() + 1).toString().padStart(2, '0');
        var day = date.getDate().toString().padStart(2, '0');
        var today = `${year}-${month}-${day}`;
        
        if (checkInDate < today) {
            alert("The check-in date must be today or later!");
            return false;
        }

        if (checkOutDate <= checkInDate) {
            alert("The check-out date must be at least one day after the check-in date!");
            return false;
        }
        // Retrieve customer_id from session (assuming it's set after successful login)
        var customerId = '<?php echo isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : ''; ?>';

        if (!customerId) {
            // Redirect to login page or handle the case where customer_id is not available
            window.location.href = 'login.php';
            return false;
        }

        // Calculate the number of days for booking
        var startDate = new Date(checkInDate);
        var endDate = new Date(checkOutDate);
        var timeDifference = endDate - startDate;
        var daysDifference = timeDifference / (1000 * 3600 * 24);

        // Calculate payment amount
        var paymentAmount = numPeople * pricePerNight * daysDifference;

        // Use AJAX to send a request to book-hotel.php with the selected hotelRoomId, numPeople, checkInDate, checkOutDate, customerId, and paymentAmount
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                console.log("Status: " + xhr.status);
                console.log("Response: " + xhr.responseText);
                // Handle the response if needed
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert(response.message);
                    // Redirect to index.php after displaying the success message
                    window.location.href = 'tour.php';
                } else {
                    alert(response.message);
                }
            }
        };

        var params = "hotelRoomId=" + hotelRoomId +
            "&numPeople=" + encodeURIComponent(numPeople) +
            "&checkInDate=" + encodeURIComponent(checkInDate) +
            "&checkOutDate=" + encodeURIComponent(checkOutDate) +
            "&customerId=" + encodeURIComponent(customerId) +
            "&paymentAmount=" + encodeURIComponent(paymentAmount);

        xhr.open("POST", "book-hotel.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(params);

        return false; // Prevent default link behavior
    }

        function redirectToLoginPage() {
        console.log("redirectToLoginPage function called");
        window.location.href = 'login.php'; // Redirect to login.php
    }

    function redirectToAdmin() {
            window.location.href = 'admin_index.php';
    }

    function redirectToCheckout() {
            window.location.href = 'checkout.php';
        }
</script>



</body>

</html>