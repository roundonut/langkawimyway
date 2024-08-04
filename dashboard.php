<?php

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
     <style>
        body {
            background-color: #fff; /* Set background color to white */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #4CAF50;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #45a049;
        }

        .dashboard-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff; /* Set background color to white */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .quote {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color:  #5F9EA0; /* Change the color to your desired color (Dodger Blue in this example) */
        }

        .tab-content {
            display: none;
        }
    </style>
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
            Celebrating the LangkawiMyWay Dream Team! Every booking, every smile, every adventure - you make it happen
        </div>

    </div>
</body>
</html>

<script>
function openTab(tabName) {
            var i, tabContent;
            tabContent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabContent.length; i++) {
                
                tabContent[i].style.display = "none";
            }
            
            document.getElementById(tabName).style.display = "block";
            
        }
</script>