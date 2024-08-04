<?php
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
    <title>LangkawiMyWay</title>
</head>

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
    </header>

    <main>
        <section class="booking-section">
            <h2>List Of Flights Available</h2>
            <!-- Flight booking interface -->
            <div id="booking-container">
                <form id="flight-booking-form" action="book-flight.php" method="post" onsubmit="return showFlightTable()">
                    <label for="num-people">Number of People:</label>
                    <input type="number" id="num-people" name="num-people" required>
                    <button type="submit">Submit</button>
                </form>

                <!-- Flight table display -->
                <div id="flight-table-container" style="display: none;">
                    <?php
                    // Assuming you have a MySQL connection established
                    require_once('db.php');

                    // Retrieve flights from the database
                    $sql = "SELECT flight_id, destination, depart_time, arrive_time, price, plane_id FROM flight";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo '<table id="flight-table">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Flight ID</th>';
                        echo '<th>Destination</th>';
                        echo '<th>Depart Time</th>';
                        echo '<th>Arrive Time</th>';
                        echo '<th>Price</th>';
                        echo '<th>Plane ID</th>';
                        echo '<th>Action</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row['flight_id'] . '</td>';
                            echo '<td>' . $row['destination'] . '</td>';
                            echo '<td>' . $row['depart_time'] . '</td>';
                            echo '<td>' . $row['arrive_time'] . '</td>';
                            echo '<td>' . $row['price'] . '</td>';
                            echo '<td>' . $row['plane_id'] . '</td>';
                            echo '<td><button class="book-btn" onclick="bookFlight(' . $row['flight_id'] . ')">Book Flight</button></td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';
                    } else {
                        echo 'No flights available.';
                    }

                    $conn->close();
                    ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>For refund, cancel, or changes, please contact our admin at 0318894829.Please make within 7 days prior booking.</p>
    </footer>

    <script>
    function showFlightTable() {
        document.getElementById('flight-table-container').style.display = 'block';
        return false; // Prevent form submission
    }

    function bookFlight(flightId) {
    console.log("Flight ID: " + flightId);

    // Assuming you have a way to get the number of people from the form
    var numPeople = document.getElementById('num-people').value;

    // Retrieve customer_id from session (assuming it's set after successful login)
    var customerId = '<?php echo isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : ''; ?>';

    if (!customerId) {
        // Redirect to login page or handle the case where customer_id is not available
        window.location.href = 'login.php';
        return false;
    }

    // Use AJAX to send a request to book-flight.php with the selected flightId, numPeople, and customerId
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
                window.location.href = 'index.php';
            } else {
                alert(response.message);
            }
        }
    };

    var params = "flightId=" + flightId + "&num-people=" + encodeURIComponent(numPeople) + "&customerId=" + encodeURIComponent(customerId);

    xhr.open("POST", "book-flight.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(params);

    return false; // Prevent default link behavior
}

    function redirectToLoginPage() {
        window.location.href = 'login.php'; // Redirect to login.php
    }
</script>


</body>

</html>




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

    #flight-table-container {
        text-align: center;
        margin-top: 20px;
    }

    #flight-table {
        margin: 0 auto;
        border-collapse: collapse;
        width: 80%; /* Adjust the width of the table */
    }

    #flight-table th, #flight-table td {
        padding: 12px;
        border: 1px solid #ddd; /* Add borders to cells for better visibility */
    }
</style>

<script>
    function redirectToCheckout() {
        window.location.href = 'checkout.php';
    }

    function showFlightTable() {
        document.getElementById('flight-table-container').style.display = 'block';
        return false; // Prevent form submission
    }

    function bookFlight(flightId) {
        console.log("Flight ID: " + flightId);

        // Assuming you have a way to get the number of people from the form
        var numPeople = document.getElementById('num-people').value;
        if (numPeople < 0) {
            alert("The number of people cannot be negative!");
            return false;
        }
        // Retrieve customer_id from session (assuming it's set after successful login)
        var customerId = '<?php echo isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : ''; ?>';

        if (!customerId) {
            // Redirect to login page or handle the case where customer_id is not available
            window.location.href = 'login.php';
            return false;
        }

        // Use AJAX to send a request to book-flight.php with the selected flightId, numPeople, and customerId
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                console.log("Status: " + xhr.status);
                console.log("Response: " + xhr.responseText);
                // Handle the response if needed
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert(response.message);
                    // Redirect to checkout.php after displaying the success message
                    window.location.href = 'hotel.php';
                } else {
                    alert(response.message);
                }
            }
        };

        var params = "flightId=" + flightId + "&num-people=" + encodeURIComponent(numPeople) + "&customerId=" + encodeURIComponent(customerId);

        xhr.open("POST", "book-flight.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(params);

        return false; // Prevent default link behavior
    }

    function redirectToLoginPage() {
        window.location.href = 'login.php'; // Redirect to login.php
    }

    function redirectToCheckout() {
            window.location.href = 'checkout.php';
        }

    function redirectToAdmin() {
            window.location.href = 'admin_index.php';
    }
</script>
